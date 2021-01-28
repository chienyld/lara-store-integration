<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Borrow;
use App\Models\Post;
use App\Models\User;
use DB;

class SendController extends Controller
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
        //$posts = Post::all();
        //return Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title','desc')->take(1)->get();
        //$posts = Post::orderBy('title','desc')->get();

        $borrows = Borrow::orderBy('id','desc')->paginate(15);
        
        return view('/send')->with('borrows', $borrows);
    }
    public function type0(){
        $posts0 = Post::orderBy('created_at','desc')->where('type', '0')->paginate(10);
        return view('posts.index')->with('posts', $posts0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'deposit' => 'required',
            'qty' => 'required'
        ]);
        $user_order_id = strval(auth()->user()->id);
        $user_order_id .= strval(date("-Ymd"));
        $user_order_id .= strval(date("-hi"));

        $ids = $request->input('id');
        $items = $request->input('name');
        $deposits = $request->input('deposit');
        $qtys = $request->input('qty');

        for($i=0 ; $i< count($ids) ; $i++ ){
        $borrow=new Borrow;
        $borrow->order_id = $user_order_id;
        $borrow->borrow_id = $ids[$i];
        $borrow->user_id = auth()->user()->id; // get this from session or wherever it came from
        $borrow->user_name = auth()->user()->name; // get this from session or wherever it came from
        $borrow->name = $items[$i];
        $borrow->depositamt = $deposits[$i];
        $borrow->qty = $qtys[$i];
        $borrow->status = false;
        $borrow->save();

        $post = Post::find($borrow->borrow_id);
        $post->inventory = $post->inventory-$borrow->qty;
        $post->save();
        }

        /*
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'deposit' => 'required',
            'qty' => 'required',
            'returndate' => 'required',
            'borrowdate' => 'required',
            'time_period' => 'required',
        ]);
        
        $borrow=new Borrow;
        $borrow->borrow_id = $request->input('id');
        $borrow->user_id = auth()->user()->id; // get this from session or wherever it came from
        $borrow->user_name = auth()->user()->name; // get this from session or wherever it came from
        $borrow->name = $request->input('name');
        $borrow->depositamt = $request->input('deposit');
        $borrow->qty = $request->input('qty');
        $borrow->borrow_date = $request->input('borrowdate');
        $borrow->return_date = $request->input('returndate');
        $borrow->time_period = $request->input('time_period');
        $borrow->status = false;
        $borrow->save();

        $post = Post::find($borrow->borrow_id);
        $post->inventory = $post->inventory-$borrow->qty;
        $post->save();*/
        return redirect('/send')->with('alert', 'created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $borrow = Borrow::find($id);
        return view('borrow.show')->with('borrow', $borrow);
    }

    public function get(Request $request)
    {
        $borrow = Borrow::find($id)->get();
        return response()->json($borrow);
    }
    /*public function get(Request $request)
    {
        $borrows = Borrow::orderBy('created_at', 'desc')->get();
        return response()->json($borrows);
    }*/
    public function verify(Request $request)
    {
        $id=$request->input('id');
        $item=$request->input('item');
        $unstatus=$request->input('status');
        $qty=$request->input('qty');
        $status=!$unstatus;
        $borrow = Borrow::find($id);
        $post = Post::find($item);
        //$borrow = Borrow::where('user_id', '=', $id, 'and', 'name', '=', $name)->get();
        $borrow->status = $status;
        if($status){
        $post->inventory = $post->inventory+$qty;
        }
        else{
        $post->inventory = $post->inventory-$qty;    
        }
        $borrow->save();
        $post->save();
        /*return response()->json([
            'status'=> $status
          ], 200);*/
        return redirect('send');
   }

    
}
