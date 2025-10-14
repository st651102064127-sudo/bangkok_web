<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Zxing\QrReader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;

class verify_slip extends Controller
{
    public function verifySlip(Request $request)
    {
        $responseData = [];
        $response = null;

        try {
            Log::info('🚀 เริ่มตรวจสอบสลิปใหม่', [
                'user' => session('user_uuid'),
                'course_id' => $request->course_id,
            ]);

            // ✅ ตรวจสอบข้อมูลที่ต้องมี
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf',
                'course_id' => 'required|integer',
                'amount' => 'required|numeric',
                'user_id' => 'required'
            ]);

            $file = $request->file('file');

            // ✅ สแกน QR Code
            $qrText = $this->scanQrCode($file);
            Log::info('✅ QR Text', ['qr' => $qrText]);

            if (!$qrText) {
                throw new \Exception('ไม่พบ QR Code ที่สามารถอ่านได้');
            }

            // ✅ ดึงข้อมูลบัญชี promptpay สำหรับคอร์สนี้
            $promptPay = DB::table('payment')
                ->where('user_id', $request->user_id)
                ->where('course_id', $request->course_id)
                ->first();

            Log::info('💾 PromptPay Data', (array) $promptPay);

            if (!$promptPay) {
                throw new \Exception('ไม่พบข้อมูลบัญชีสำหรับคอร์สนี้');
            }

            // ✅ เตรียม payload สำหรับ Slip2Go
            $payload = [
                'checkDuplicate' => false,
                'checkReceiver' => [[
                    'accountType' => '01004',
                    'accountNumber' => env('payment_number'),
                    'accountNameTH' => env('payment_name'),
                ]],
                'checkAmount' => [
                    'type' => 'eq',
                    'amount' => (float) $request->amount,
                ],
                'checkDate' => [
                    'type' => 'eq',
                    'date' => now()->format('Y-m-d'),
                ],
            ];

            Log::info('📤 ส่งข้อมูลไป Slip2Go', [
                'api_url' => env('Api_Url'),
                'payload' => $payload
            ]);

            // ✅ เรียก API Slip2Go
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('Secret_Key'),
            ])->attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            )->post(env('Api_Url'), [
                'payload' => json_encode($payload),
                'verify' => false,
            ]);

            Log::info('📩 API Response Received', [
                'status' => $response->status(),
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                throw new \Exception('API ตอบกลับไม่สำเร็จ: ' . $response->body());
            }

            // ✅ ตรวจสอบข้อมูลใน response
            if (($responseData['code'] ?? '') === '200501') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'สลิปนี้ถูกใช้งานแล้ว กรุณาตรวจสอบสลิปหรือติดต่อผู้ดูแลระบบ'
                ], 400);
            }

            $paidAmount = $responseData['data']['amount'] ?? 0;
            $receiverName = $responseData['data']['receiver']['account']['name'] ?? '';

            Log::info('💰 ตรวจสอบยอดและชื่อบัญชี', [
                'paidAmount' => $paidAmount,
                'expectedAmount' => $request->amount,
                'receiverName' => $receiverName,
                'expectedReceiverName' => env('payment_name')
            ]);

            // ✅ ตรวจสอบยอดเงิน
            if (floatval($paidAmount) !== floatval($request->amount)) {
                throw new \Exception('ยอดเงินไม่ตรง กรุณาตรวจสอบสลิป');
            }

            // ✅ ตรวจสอบชื่อบัญชีผู้รับเงิน
            $cleanReceiver = $this->removeTitle($receiverName);
            if (strcasecmp($cleanReceiver, env('payment_name')) !== 0) {
                throw new \Exception('ชื่อบัญชีผู้รับเงินไม่ตรง กรุณาตรวจสอบสลิป');
            }

            // ✅ บันทึกการชำระเงินลง DB
            DB::table('enrollments')
                ->where('user_id', session('user_uuid'))
                ->where('course_id', $request->course_id)
                ->update([
                    'ref' => $qrText,
                    'status' => 'completed',
                    'payment_id' => Str::uuid(),
                    'updated_at' => now()
                ]);

            Log::info('✅ บันทึกข้อมูลการชำระเงินสำเร็จ', [
                'user' => session('user_uuid'),
                'course_id' => $request->course_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'ตรวจสอบสลิปสำเร็จและบันทึกข้อมูลเรียบร้อยแล้ว',
                'data' => $responseData
            ]);

        } catch (\Exception $err) {
            Log::error('❌ QR/Slip Error: ' . $err->getMessage(), [
                'file' => $file->getClientOriginalName() ?? 'unknown',
                'response' => $response ? $response->body() : 'no response',
                'responseData' => $responseData,
                'trace' => $err->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการตรวจสอบสลิป',
                'status' => 'error',
                'detail' => $err->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ ลบคำนำหน้าออกจากชื่อ
     */
    private function removeTitle($name): string
    {
        $titles = ['นาย', 'นาง', 'น.ส.', 'น.ส', 'นางสาว'];
        foreach ($titles as $title) {
            if (mb_substr($name, 0, mb_strlen($title)) === $title) {
                return trim(mb_substr($name, mb_strlen($title)));
            }
        }
        return trim($name);
    }

    /**
     * ✅ ฟังก์ชันสแกน QR
     */
    private function scanQrCode($file)
    {
        try {
            $filePath = $file->getRealPath();
            $qrcode = new QrReader($filePath);
            $text = $qrcode->text();

            if (!$text) {
                Log::warning('⚠️ QR not found or unreadable in image', [
                    'file' => $file->getClientOriginalName()
                ]);
                return null;
            }

            Log::info('✅ QR Scan Result: ' . $text);
            return $text;
        } catch (\Exception $e) {
            Log::error('❌ QR Decode Error: ' . $e->getMessage());
            return null;
        }
    }
}
