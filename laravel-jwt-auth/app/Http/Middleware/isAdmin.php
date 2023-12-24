<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Break_;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next): Response
    {
    
        
        if(Auth::check() && Auth::user()->role == 'admin'){
            return $next($request);
        }
        else {
        return response()->json(['message' => 'you can not access']);
    }
    
        return $next($request);
}

// public function handle($request, Closure $next, ...$roles)
// {
//     if (!Auth::check()) {
//         return response('hate you');
//     }


//     foreach ($roles as $role) {
//         if (Auth::user()->hasRole) {
//             return $next($request);
//         }
//     }

//         return response('fuck you');
// }
}