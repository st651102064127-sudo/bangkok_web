<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
  public function handle(Request $request, Closure $next)
{
    // ถ้าไม่มี session เลย → กลับไป login
    if (!session()->has('user_status')) {
        return redirect()->route('User.Login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
    }

    // ถ้าเป็น admin → ปล่อยผ่านไปต่อ
    if (session()->get('user_status') === 'admin') {
        return $next($request);
    }

    // ถ้าเป็น user ธรรมดา → ส่งไปหน้า index
    return redirect()->route('User.Index')->with('error', 'คุณไม่มีสิทธิ์เข้าใช้งานส่วนนี้');
}

}
