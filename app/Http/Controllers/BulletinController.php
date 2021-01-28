<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulletin;
use DB;

class BulletinController extends Controller
{
    public function index(){
        return view('bulletin');
    }

    public function edit(Request $request)
    {
		$bulletin = Bulletin::find(1);
        
        $bulletin->content = $request->input('content');
        $bulletin->save();

        return redirect('/bulletin')->with('success', 'Bulletin Updated');
    }

}
