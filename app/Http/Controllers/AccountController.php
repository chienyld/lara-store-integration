<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    
    public function index()
    {
        
        $accounts = User::orderBy('id','desc')->where('id','>','1')->paginate(10);
        
        return view('/account')->with('accounts', $accounts);
    }
    public function edit(Request $request)
    {
        $id=$request->input('id');
        $user = User::find($id);
        $unstatus=$request->input('status');
        $status=!$unstatus;
        $user->active = $status;
        
        $user->save();
        return redirect('account');
   }
}
