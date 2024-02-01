<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; //It handles authentication
use App\Models\User; //representing users table

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     # IMPORTANT NOTE: The handle() method handles the incoming request
     # and interact with the current HTTP request being handled by our application
     # as well as retrieve the input, cookies, and files that were submitted by
     # the request.
    public function handle(Request $request, Closure $next): Response
    {   
        # The Auth::check() ---> check the user if it's logged-in
        if (Auth::check() && Auth::user()->role_id == User::ADMIN_ROLE_ID) { //1
            #NOTE: if the role_id == 1, then that user is an Administrator

            return $next($request);    
        }
        return redirect()->route('index');
    }
}
