<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulletin;
use DB;

class RulesController extends Controller
{
    public function index(){
        $bulletin = Bulletin::find(2);
        $rules = $bulletin->content;
        return view('about')->with('rules', $rules);
    }
    public function page(){
        return view('rules');
    }
    public function edit(Request $request)
    {
		$bulletin = Bulletin::find(2);
        
        $bulletin->content = $request->input('content');
        $bulletin->save();

        return redirect('/about')->with('success', 'Rules Updated');
    }


}
