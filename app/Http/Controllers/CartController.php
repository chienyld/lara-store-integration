<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Darryldecode\Cart\CartCondition;

class CartController extends Controller
{
    
    public function index()
    {
        $userId = auth()->user()->id;
        if(request()->ajax())
        {
            $items = [];
            

            \Cart::session($userId)->getContent()->each(function($item) use (&$items)
            {
                $items[] = $item;
            });

            return response(array(
                'success' => true,
                'data' => $items,
                'message' => 'cart get items success'
            ),200,[]);
            
        }
        else
        {
            return view('cart');
            //return redirect()->route('/cart')->with('alert', 'Hello');

        }
    }

    public function add(Request $request)
    {
        
        $userId = auth()->user()->id; // get this from session or wherever it came from
        $inventory = $request->input('inventory');
        $id = $request->input('id');
        $name = $request->input('name');
        $price = $request->input('price');
        $qty = $request->input('qty');
        if ($qty>0){

        if ($inventory>=$qty && $qty>0){

        $item = \Cart::session($userId)->add($id, $name, $price, $qty);

        /*return response(array(
            'success' => true,
            'data' => $item,
            'message' => "item added."
        ),201,[]);*/
        return view('/cart')->with(array(
            'success' => true,
            'data' => $item,
            'message' => "item added."
        ),201,[]);
        }
        else{
        //return redirect('/posts');    
        return redirect()->back() ->with('alert', '請輸入有效數值！');
        }
        }
        else{
        return redirect()->back() ->with('alert', '請輸入有效數值！');    
        }
    }

    public function addCondition()
    {
        $userId = auth()->user()->id;

        /** @var \Illuminate\Validation\Validator $v */
        $v = validator(request()->all(),[
            'name' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
            'value' => 'required|string',
        ]);

        if($v->fails())
        {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => $v->errors()->first()
            ),400,[]);
        }

        $name = request('name');
        $type = request('type');
        $target = request('target');
        $value = request('value');

        $cartCondition = new CartCondition([
            'name' => $name,
            'type' => $type,
            'target' => $target, // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $value,
            'attributes' => array()
        ]);

        \Cart::session($userId)->condition($cartCondition);

        return response(array(
            'success' => true,
            'data' => $cartCondition,
            'message' => "condition added."
        ),201,[]);
    }

    public function clearCartConditions()
    {
        $userId = auth()->user()->id;// get this from session or wherever it came from

        \Cart::session($userId)->clearCartConditions();

        return response(array(
            'success' => true,
            'data' => [],
            'message' => "cart conditions cleared."
        ),200,[]);
    }

    public function delete($id)
    {
        $userId = auth()->user()->id; // get this from session or wherever it came from
        
        \Cart::session($userId)->remove($id);

        return response(array(
            'success' => true,
            'data' => $id,
            'message' => "cart item {$id} removed."
        ),200,[]);
        
        //return view('cart');
        //return redirect('cart')->back()->with(\Session::flash('flash_message', 'Hello'));
        //return redirect()->route('cart')->with('alert', 'Hello');
        
    }

    public function details()
    {
        $userId = auth()->user()->id; // get this from session or wherever it came from

        // get subtotal applied condition amount
        $conditions = \Cart::session($userId)->getConditions();


        // get conditions that are applied to cart sub totals
        $subTotalConditions = $conditions->filter(function (CartCondition $condition) {
            return $condition->getTarget() == 'subtotal';
        })->map(function(CartCondition $c) use ($userId) {
            return [
                'name' => $c->getName(),
                'type' => $c->getType(),
                'target' => $c->getTarget(),
                'value' => $c->getValue(),
            ];
        });

        // get conditions that are applied to cart totals
        $totalConditions = $conditions->filter(function (CartCondition $condition) {
            return $condition->getTarget() == 'total';
        })->map(function(CartCondition $c) {
            return [
                'name' => $c->getName(),
                'type' => $c->getType(),
                'target' => $c->getTarget(),
                'value' => $c->getValue(),
            ];
        });

        return response(array(
            'success' => true,
            'data' => array(
                'total_quantity' => \Cart::session($userId)->getTotalQuantity(),
                'sub_total' => \Cart::session($userId)->getSubTotal(),
                'total' => \Cart::session($userId)->getTotal(),
                'cart_sub_total_conditions_count' => $subTotalConditions->count(),
                'cart_total_conditions_count' => $totalConditions->count(),
            ),
            'message' => "Get cart details success."
        ),200,[]);
    }
}
