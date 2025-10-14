<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee_Model;
use App\Models\Login_Model;

class Login_Controller extends Controller
{
    public function login(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'email_account' => 'required|email',
            'password_account' => 'required'
        ]);

        // ✅ หา Account
        $account = Login_Model::where('email_account', $request->email_account)->first();

        if (!$account) {
            return back()->withErrors(['email_account' => 'ไม่พบบัญชีผู้ใช้']);
        }

        // ✅ ตรวจสอบว่าโดนล็อกไหม
        if ($account->lock_account) {
            if (now()->lt($account->ban_account)) {
                return back()->withErrors(['email_account' => 'บัญชีของคุณถูกล็อกชั่วคราว']);
            } else {
                // ✅ ปลดล็อกหากพ้นเวลา
                $account->update([
                    'lock_account' => false,
                    'login_count_account' => 0,
                    'ban_account' => null,
                ]);
            }
        }

        // ✅ ตรวจสอบ password
        if (!Hash::check($request->password_account, $account->password_account)) {
            $account->increment('login_count_account');



            return back()->withErrors(['password_account' => 'รหัสผ่านไม่ถูกต้อง']);
        }

        // ✅ Login สำเร็จ รีเซ็ต login count
        $account->update(['login_count_account' => 0]);

        // ✅ ดึงข้อมูล user (Employee)
        $user = Employee_Model::where('email', $request->email_account)->first();

        if (!$user) {
            return back()->withErrors(['email_account' => 'ไม่พบข้อมูลผู้ใช้']);
        }

        // ✅ เก็บข้อมูลลง session
        session()->put('user_uuid', $user->uuid); // ตรงกับ middleware
        session()->put('user_fullname', $user->fullname);
        session()->put('user_status', $user->status);


        if ($user->status == "Admin") {
            return redirect()->route('Admin.Dashboard')->with('success', 'เข้าสู่ระบบสำเร็จ');
        } else {
            return redirect()->route('index')->with('success', 'เข้าสู่ระสำเร็จ');
        }
    }
}
