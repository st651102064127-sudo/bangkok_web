<?php
namespace App\Http\Controllers;

use App\Models\Login_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Employee_Model;
use App\Models\Account;

class Register_Controller extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {


            $request->validate([
                'username' => 'required|string|max:255|unique:users,fullname',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'min:8',
                ],
                'password_confirmation' => 'required|same:password',
            ], [
                'username.required' => 'กรุณากรอกชื่อผู้ใช้',
                'username.unique' => 'ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้มีอยู่ในระบบแล้ว',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                'password_confirmation.required' => 'กรุณายืนยันรหัสผ่าน',
                'password_confirmation.same' => 'รหัสผ่านไม่ตรงกัน',
            ]);

            $uuid = Str::uuid()->toString();

            Employee_Model::create([
                'uuid' => $uuid,
                'fullname' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

            Login_Model::create([
                'email_account' => $request->email,
                'password_account' => Hash::make($request->password),
                'login_count_account' => 0,
                'lock_account' => false,
            ]);



            return response()->json(['success' => true, 'message' => 'สมัครสมาชิกสำเร็จ']);



        } catch (\Illuminate\Validation\ValidationException $e) {
            log::error($e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
