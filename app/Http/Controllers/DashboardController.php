<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // จำนวนผู้ใช้ทั้งหมด
        $totalUsers = DB::table('users')->count();

        // จำนวนคอร์สทั้งหมด
        $totalCourses = DB::table('Courses')->count(); // ใช้ชื่อ table ตาม DB จริง

        // จำนวนการลงทะเบียนทั้งหมด
        $totalRegistrations = DB::table('enrollments')->count();

        // รายได้รวมจาก enrollments (สมมติ field เป็น payment_amount)
        $totalRevenue = DB::table('enrollments')->sum('payment_amount');

        // ผู้ใช้ล่าสุด 5 คน
        $latestUsers = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // จำนวนผู้ใช้ Active / Inactive
        $activeUsers = DB::table('users')->where('status', 'Active')->count();
        $inactiveUsers = DB::table('users')->where('status', 'Inactive')->count();

        // การลงทะเบียน Paid / Pending / Canceled
        $paidRegistrations = DB::table('enrollments')->where('status', 'Paid')->count();
        $pendingRegistrations = DB::table('enrollments')->where('status', 'Pending')->count();
        $canceledRegistrations = DB::table('enrollments')->where('status', 'Canceled')->count();

        // ลงทะเบียนล่าสุด 5 รายการ พร้อม join course เพื่อดึงชื่อคอร์ส
        $latestRegistrations = DB::table('enrollments as e')
            ->join('Courses as c', 'e.course_id', '=', 'c.course_id')
            ->select('e.*', 'c.title as course_title', 'c.price as course_price')
            ->orderBy('e.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCourses',
            'totalRegistrations',
            'totalRevenue',
            'latestUsers',
            'latestRegistrations',
            'activeUsers',
            'inactiveUsers',
            'paidRegistrations',
            'pendingRegistrations',
            'canceledRegistrations'
        ));
    }
}
