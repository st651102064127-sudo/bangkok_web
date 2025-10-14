<?php

namespace App\Http\Controllers;

use App\Models\Employee_Model;
use Illuminate\Container\Attributes\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mail;

class ForgotPassword_Controller extends Controller
{

    public function resetPassword(Request $request)
    {
        // ✅ ตรวจสอบข้อมูลที่กรอกมา
        $request->validate([
            'email_account' => 'required|email',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'email_account.required' => 'กรุณากรอกอีเมล',
            'email_account.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'new_password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'new_password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'confirm_password.required' => 'กรุณายืนยันรหัสผ่าน',
            'confirm_password.same' => 'รหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        // ✅ ค้นหาผู้ใช้จาก email
        $user = Employee_Model::where('email', $request->email_account)->first();


        if (!$user) {
            return redirect()->back()->with('error', 'ไม่พบอีเมลในระบบ');
        }

        try {
            // ✅ อัปเดตรหัสผ่านใหม่
            $user->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);
            DB::table('account')
                ->where('email_account', $request->email_account)
                ->update([
                    'password_account' => Hash::make($request->new_password),
                    'updated_at' => now()
                ]);
            return redirect()->route('User.Login')->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว กรุณาเข้าสู่ระบบใหม่');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
        }
    }



}


