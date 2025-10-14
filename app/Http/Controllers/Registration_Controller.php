<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration_Model;
use Illuminate\Support\Facades\DB;
class Registration_Controller extends Controller
{
    public function index()
    {
        $registrations = DB::table('enrollments')->get();

        return view('admin.Registration', compact('registrations'));
    }
}
