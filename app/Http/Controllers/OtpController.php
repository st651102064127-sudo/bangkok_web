<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailOtp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\OtpMail;

class OtpController extends Controller
{
    // ส่ง OTP
    public function sendOtp(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            // ถ้าเป็นการขอ OTP ใหม่ (flag หรือ button ใหม่) -> สร้าง OTP ใหม่เสมอ
            $otp = rand(100000, 999999);
            $otpRecord = EmailOtp::create([
                'id' => Str::uuid(),
                'email' => $request->email,
                'otp' => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(5),
            ]);

            // ส่งอีเมล OTP ใหม่
            Mail::to($request->email)->send(new OtpMail($otp));

            // เก็บ session อ้างอิง OTP id
            session([
                'otp_email' => $request->email,
                'otp_id' => $otpRecord->id,
                'otp_expires_at' => $otpRecord->expires_at,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP ถูกส่งไปแล้ว'
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่ง OTP ได้',
            ]);
        }
    }




    // ตรวจสอบ OTP
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string',
            ]);

            // ตรวจสอบจาก session ก่อน
            if (session('otp_email') !== $request->email) {
                return response()->json(['success' => false, 'message' => 'OTP ไม่ตรงกับอีเมลนี้']);
            }

            $otpRecord = EmailOtp::where('id', session('otp_id'))->first();
            if (!$otpRecord) {
                return response()->json(['success' => false, 'message' => 'OTP ไม่ถูกต้อง']);
            }

            if (Carbon::parse($otpRecord->expires_at)->isPast()) {
                return response()->json(['success' => false, 'message' => 'OTP หมดอายุแล้ว']);
            }

            if (!Hash::check($request->otp, $otpRecord->otp)) {
                return response()->json(['success' => false, 'message' => 'OTP ไม่ถูกต้อง']);
            }

            return response()->json(['success' => true, 'message' => 'OTP ถูกต้อง']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
        }
    }


}
