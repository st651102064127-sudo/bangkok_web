<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PromptPayQR\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Payment extends Controller
{
    public function showQr($price)
    {

        $promptPayId = env('payment_number');


        // สร้าง QR Code Payload
        $payload = Builder::staticMerchantPresentedQR($promptPayId)
            ->setAmount($price)
            ->build();

        return $payload;
    }

    public function Enrollment($data)
    {
        try {
            $user_id = session('user_uuid');
            $course_id = $data['course_id'];

            // ✅ เช็ค enrollment ทุกสถานะ
            $existingEnrollment = DB::table('enrollments')
                ->where('course_id', $course_id)
                ->where('user_id', $user_id)
                ->first();

            if ($existingEnrollment) {
                if ($existingEnrollment->status === 'completed') {
                    Log::info("User already completed payment for this course");
                    return '0'; // ชำระเงินแล้ว
                } else {
                    Log::info("Using existing enrollment with status: " . $existingEnrollment->status);
                    return $existingEnrollment->payment_id; // ใช้อันเดิม (pending, paid, ฯลฯ)
                }
            }

            // ✅ สร้างใหม่ถ้าไม่มีอยู่
            $payment_id = (string) \Illuminate\Support\Str::uuid();

            DB::table('payment')->insert([
                'payment_id' => $payment_id,
                'user_id'    => $user_id,
                'course_id'  => $course_id,
                'amount'     => $data['price'] ?? 0,
                'status'     => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('enrollments')->insert([
                'user_id'         => $user_id,
                'course_id'       => $course_id,
                'payment_id'      => $payment_id,
                'payment_amount'  => $data['price'] ?? 0,
                'status'          => 'pending',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            Log::info("New Payment and Enrollment created. Payment ID: " . $payment_id);
            return $payment_id;
        } catch (\Exception $e) {
            Log::error("Enrollment failed: " . $e->getMessage());
            return '0';
        }
    }
}
