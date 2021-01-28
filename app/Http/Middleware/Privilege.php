<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
use Closure;

class Privilege extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        //$id=$request->input('id');
        //$user = User::find($id);
        if ($user->active==false) {
            return redirect('/nopermission');
        }
        return $next($request);
    }
}
