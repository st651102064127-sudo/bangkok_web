<?php

namespace App\Http\Controllers;

use App\Models\Courses_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Payment;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\select;

class CoursesUser_controller extends Controller
{
    function index()
    {
        try {
            $data = Courses_Model::all();
            return view('User.courses', ['data' => $data]);
        } catch (\Exception $e) {
            // ส่งข้อความ error ไปยัง view
            return view('User.courses', ['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }
public function detail($id)
{
    // ดึงข้อมูลคอร์ส + syllabus + feature
    $courses = DB::table('courses')
        ->leftJoin('syllabuses', 'courses.course_id', '=', 'syllabuses.course_id')
        ->leftJoin('coursefeatures', 'courses.course_id', '=', 'coursefeatures.course_id')
        ->where('courses.course_id', $id)
        ->select(
            'courses.course_id',
            'courses.title',
            'courses.category',
            'courses.instructor',
            'courses.duration',
            'courses.level',
            'courses.price',
            'courses.image_url',
            'courses.description',

            'syllabuses.syllabus_id',
            'syllabuses.title as syllabus_title',
            'syllabuses.duration as syllabus_duration',

            'syllabuses.video_url',

            'coursefeatures.feature_id',
            'coursefeatures.feature_name',
            'coursefeatures.feature_value'
        )
        ->get();

    if ($courses->isEmpty()) {
        abort(404, 'ไม่พบคอร์สนี้');
    }

    $result = $courses->groupBy('course_id')->map(function ($courseGroup) {
        $course = $courseGroup->first();

        return [
            'course_id' => $course->course_id,
            'title' => $course->title,
            'category' => $course->category,
            'instructor' => $course->instructor,
            'duration' => $course->duration,
            'level' => $course->level,
            'price' => $course->price,
            'image_url' => $course->image_url,
            'description' => $course->description,

            'syllabuses' => $courseGroup->map(function ($item) {
                if (!empty($item->syllabus_id)) {
                    return [
                        'syllabus_id' => $item->syllabus_id,
                        'title' => $item->syllabus_title ?? '',
                        'duration' => $item->syllabus_duration ?? '',

                        'video_url' => $item->video_url ?? '',
                    ];
                }
                return null;
            })->filter()->unique('syllabus_id')->values()->toArray(),

            'features' => $courseGroup->map(function ($item) {
                if (!empty($item->feature_id)) {
                    return [
                        'feature_id' => $item->feature_id,
                        'feature_name' => $item->feature_name ?? '',
                        'feature_value' => $item->feature_value ?? '',
                    ];
                }
                return null;
            })->filter()->unique('feature_id')->values()->toArray(),
        ];
    })->first();

    // ตรวจสอบว่าผู้ใช้ซื้อคอร์สแล้วหรือยัง
    $userId = session('user_uuid');

    $enrollment = DB::table('enrollments')
        ->where('course_id', $id)
        ->where('user_id', $userId)
        ->where('status', 'completed')
        ->exists();

    // ถ้ายังไม่ซื้อ ให้ซ่อนวิดีโอทั้งหมด
    if (!$enrollment) {
        $result['syllabuses'] = collect($result['syllabuses'])->map(function ($item) {
            $item['video_url'] = null; // ซ่อน vdo
            return $item;
        })->toArray();
    }

    return view('User.courses_detail', compact('result', 'enrollment'));
}



    public function payment($id)
    {

        $courses = DB::table('courses')
            ->leftJoin('Syllabuses', 'courses.course_id', '=', 'Syllabuses.course_id')
            ->leftJoin('CourseFeatures', 'courses.course_id', '=', 'CourseFeatures.course_id')
            ->where('courses.course_id', $id)
            ->select(
                'courses.course_id',
                'courses.title',
                'courses.category',
                'courses.instructor',
                'courses.duration',
                'courses.level',
                'courses.price',
                'courses.image_url',
                'courses.description',

                'Syllabuses.syllabus_id',
                'Syllabuses.title as syllabus_title',
                'Syllabuses.duration as syllabus_duration',


                'CourseFeatures.feature_id',
                'CourseFeatures.feature_name',
                'CourseFeatures.feature_value'
            )
            ->get();


        $result = $courses->groupBy('course_id')->map(function ($courseGroup) {
            $course = $courseGroup->first();

            return [
                'course_id' => $course->course_id,
                'title' => $course->title,
                'category' => $course->category,
                'instructor' => $course->instructor,
                'duration' => $course->duration,
                'level' => $course->level,
                'price' => $course->price,
                'image_url' => $course->image_url,
                'description' => $course->description,

                'syllabuses' => $courseGroup->map(function ($item) {
                    if (!empty($item->syllabus_id)) {
                        return [
                            'syllabus_id' => $item->syllabus_id,
                            'title' => $item->syllabus_title ?? '',
                            'duration' => $item->syllabus_duration ?? '',

                        ];
                    }
                    return null;
                })->filter()->unique('syllabus_id')->values()->toArray(),

                'features' => $courseGroup->map(function ($item) {
                    if (!empty($item->feature_id)) {
                        return [
                            'feature_id' => $item->feature_id,
                            'feature_name' => $item->feature_name ?? '',
                            'feature_value' => $item->feature_value ?? '',
                        ];
                    }
                    return null;
                })->filter()->unique('feature_id')->values()->toArray(),
            ];
        })->first(); // ใช้ first() เพราะเราดึง course เดียว

        $payment = new Payment();



        $data = [
            'course_id' => $result['course_id'],
            'price' => $result['price']
        ];
        $Enroll = $payment->Enrollment($data);
        $qr = $payment->showQr($result['price']);

        if ($Enroll == '0') {
            return redirect()->route('courses.detail', $data['course_id'])->with([
                'status' => 'warning',
                'message' => 'คุณมีคอร์สนี้เเล้ว'
            ]);
        }

        return view('User.payment')->with('course', $result)->with('qrcode', $qr);
    }

    function free(Request $req)
    {
        $result = DB::table('enrollments')
            ->where('course_id', $req->course_id)
            ->where('user_id', session('user_uuid'))
            ->first();

        if ($result) {
            DB::table('enrollments')
                ->where('enroll_id', $result->enroll_id) // ใช้ primary key หรือ id ของ record
                ->update(['status' => 'completed']);
        }
        return redirect()->route('courses.detail', $result->course_id);
    }
}
