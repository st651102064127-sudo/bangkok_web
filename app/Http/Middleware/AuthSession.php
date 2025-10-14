<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{

    public function handle(Request $request, Closure $next): Response
    {

         if (session()->get('user_status') != 'Admin') {

            return redirect()->route('User.Login')->with('error', 'คุณไม่มีสิทธิ์เข้าใช้งานส่วนนี้');
        }
        return $next($request);
    }
}
