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
    public function myorder()
    {
        $user = auth()->user()->id;
        $orders = Order::where('user_id',$user)->orderBy('id','desc')->paginate(15);
        return view('/order')->with('orders', $orders);
        //return view('/send')->with('borrows', $borrows);
    }
    
    public function verify(Request $request)
    {
        $id=$request->input('id');
        $unstatus=$request->input('status');
        $status=!$unstatus;
        $order = Order::find($id);
        $order->status = $status;
        
        $order->save();
        /*return response()->json([
            'status'=> $status
          ], 200);*/    
        return redirect()->route('/order');
}

    
}
