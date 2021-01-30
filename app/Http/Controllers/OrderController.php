<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Borrow;
use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use DB;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void   
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('id','desc')->paginate(15);
        
        return view('/order')->with('orders', $orders);
    }
    
    public function verify(Request $request)
    {
        $id=$request->input('id');
        $item=$request->input('item');
        $unstatus=$request->input('status');
        $qty=$request->input('qty');
        $status=!$unstatus;
        $order = Order::find($id);
        $borrow->status = $status;
        
        $borrow->save();
        /*return response()->json([
            'status'=> $status
          ], 200);*/
        return redirect('order');
   }

    
}
