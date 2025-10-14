<?php

namespace App\Http\Controllers;

use App\Models\Courses_Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Syllabus;
use App\Models\CourseFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
class Courses_Controller extends Controller
{
    private $uploadPath = 'uploads/Courses/';

    public function index()
    {
        // ดึงข้อมูลคอร์สทั้งหมดจากฐานข้อมูล
        $courses = Courses_Model::all();

        // ส่งข้อมูลไปยัง View
        return view('Admin.Coures', compact('courses'));
    }
    public function store(Request $req)
    {
        \Log::info($req->all());

        $validator = Validator::make($req->all(), [
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'instructor' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'syllabuses.*.title' => 'required|string',
            'syllabuses.*.duration' => 'nullable|string',
            'syllabuses.*.video_url' => 'nullable|string',
            'features.*.feature_name' => 'required|string',
            'features.*.feature_value' => 'required|string',
        ], [
            'title.required' => 'กรุณากรอกชื่อคอร์ส',
            'instructor.required' => 'กรุณากรอกชื่อผู้สอน',
            'price.required' => 'กรุณากรอกราคา',
            'syllabuses.*.title.required' => 'กรุณากรอกชื่อบทเรียน',
            'features.*.feature_name.required' => 'กรุณากรอกชื่อคุณสมบัติ',
            'features.*.feature_value.required' => 'กรุณากรอกรายละเอียดคุณสมบัติ',
        ]);

        $validator->validate();

        DB::transaction(function () use ($req) {

            // Upload image
            $image_url = null;
            if ($req->hasFile('image')) {
                $file = $req->file('image');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/courses'), $filename);
                $image_url = 'uploads/courses/' . $filename;
            }

            // Calculate total duration
            $totalHours = 0;
            if ($req->has('syllabuses')) {
                foreach ($req->syllabuses as $syll) {
                    if (!empty($syll['duration'])) {
                        if (str_contains($syll['duration'], ':')) {
                            [$h, $m] = explode(':', $syll['duration']);
                            $totalHours += intval($h) * 60 + intval($m);
                        } else {
                            $totalHours += intval($syll['duration']);
                        }
                    }
                }
            }

            // Create course
            $course = Courses_Model::create([
                'title' => $req->title,
                'category' => $req->category,
                'instructor' => $req->instructor,
                'duration' => $totalHours,
                'level' => $req->level,
                'price' => $req->price,
                'image_url' => $image_url,
                'description' => $req->description,
            ]);

            // Create syllabuses
            if ($req->has('syllabuses')) {
                Log::info($req->has('syllabuses'));
                $syllabuses = [];
                foreach ($req->syllabuses as $syll) {

                    $syllabuses[] = [
                        'title' => $syll['title'],
                        'duration' => $syll['duration'] ?? null,
                        'video_url' => $syll['video_url'] ?? null, // ใช้ค่า hidden input จาก AJAX
                    ];
                }
                $course->syllabuses()->createMany($syllabuses);
            }

            // Create features
            if ($req->has('features')) {
                $course->features()->createMany($req->features);
            }
        });

        return redirect()->route('Courses.Index')->with('success', 'เพิ่มคอร์สเรียบร้อยแล้ว');
    }

    public function update(Request $req, $uuid)
    {
        try {
            // ดึงคอร์สตาม UUID
            $course = Courses_Model::where('course_id', $uuid)->firstOrFail();

            DB::transaction(function () use ($req, $course) {
                // อัปโหลดรูปใหม่ถ้ามี และลบรูปเก่า
                if ($req->hasFile('image')) {
                    if ($course->image_url && file_exists(public_path($course->image_url))) {
                        unlink(public_path($course->image_url));
                    }
                    $file = $req->file('image');
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = 'uploads/courses/' . $filename;
                    $file->move(public_path('uploads/courses'), $filename);
                    $course->image_url = $path;
                }

                // คำนวณระยะเวลา รวมทุก Syllabus
                $totalMinutes = 0;
                if ($req->has('syllabuses')) {
                    foreach ($req->syllabuses as $syll) {
                        if (!empty($syll['duration'])) {
                            if (str_contains($syll['duration'], ':')) {
                                [$h, $m] = explode(':', $syll['duration']);
                                $totalMinutes += intval($h) * 60 + intval($m);
                            } else {
                                $totalMinutes += intval($syll['duration']);
                            }
                        }
                    }
                }

                // อัปเดตข้อมูลหลัก
                $course->title = $req->title;
                $course->category = $req->category;
                $course->instructor = $req->instructor;
                $course->level = $req->level;
                $course->price = $req->price;
                $course->description = $req->description;
                $course->duration = $totalMinutes;
                $course->save();

                // อัปเดต Syllabuses
                if ($req->has('syllabuses')) {
                    $course->syllabuses()->delete(); // ลบของเก่า

                    $syllabuses = [];
                    foreach ($req->syllabuses as $syll) {
                        $syllabuses[] = [
                            'title' => $syll['title'],
                            'duration' => $syll['duration'] ?? null,
                            'video_url' => $syll['video_url'] ?? null, // เก็บ path video ถ้ามี
                        ];
                    }
                    $course->syllabuses()->createMany($syllabuses);
                }

                // อัปเดต Features
                if ($req->has('features')) {
                    $course->features()->delete();
                    $course->features()->createMany($req->features);
                }
            });

            return redirect()->route('Courses.Index')->with('success', 'แก้ไขข้อมูลสำเร็จ');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }


    public function destroy($uuid)
    {
        try {
            $course = Courses_Model::where('course_id', $uuid)->firstOrFail();

            DB::transaction(function () use ($course) {
                // ลบรูปคอร์สเก่า
                if ($course->image_url && file_exists(public_path($course->image_url))) {
                    unlink(public_path($course->image_url));
                }

                // ลบวิดีโอใน syllabuses
                foreach ($course->syllabuses as $syll) {
                    if ($syll->video_url && file_exists(public_path($syll->video_url))) {
                        unlink(public_path($syll->video_url));
                    }
                }

                // ลบ syllabuses และ features
                $course->syllabuses()->delete();
                $course->features()->delete();

                // ลบตัวคอร์ส
                $course->delete();
            });

            return redirect()->route('Courses.Index')->with('success', 'ลบคอร์สสำเร็จ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function uploadVideo(Request $request)
    {
        // Validation
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime', // 50MB
        ]);

        try {
            $file = $request->file('video');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/videos');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $filename);

            return response()->json([
                'status' => 'success',
                'path' => 'uploads/videos/' . $filename,
                'url' => asset('uploads/videos/' . $filename)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function update_video(Request $request)
    {
        log::info($request);
        $request->validate([
            'video' => 'required|mimes:mp4,avi,mov',
        ]);

        $file = $request->file('video');

        // ลบ video เก่า
        if ($request->old_video && file_exists(public_path($request->old_video))) {
            unlink(public_path($request->old_video));
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/videos'), $filename);
        $path = 'uploads/videos/' . $filename;

        return response()->json([
            'status' => 'success',
            'url' => asset($path),
            'path' => $path,
        ]);
    }
}

