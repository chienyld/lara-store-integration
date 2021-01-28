<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user_privilege = auth()->user()->privilege=='sa_admin';
        //$users = User::find($user_privilege);
        $users = User::where('privilege', 'sa_admin')->orderBy('name', 'desc')->get();
        $arr=[];
        $parr=[];
        foreach ($users as $user){
            array_push($arr, $user->id);
            //$user->id;
            $posts=POST::where('user_id',$user->id)->orderBy('id', 'desc')->get();
            foreach ($posts as $post){
                array_push($parr,$post);
            }
        }
        return view('dashboard')->with('posts', $parr);
        
    }
}