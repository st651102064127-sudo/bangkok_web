<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Payment;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <<<<< ✅ สำคัญมาก
use App\Http\Controllers\Courses_Controller;
use App\Http\Controllers\Employee_Controller;
use App\Http\Controllers\Registration_Controller;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\Register_Controller;
use App\Http\Middleware\AuthSession;
use App\Http\Controllers\ForgotPassword_Controller;
use App\Http\Controllers\CoursesUser_controller;
use App\Http\Controllers\Api\verify_slip;

//Route::middleware(AuthSession::class)->group(function () {
Route::get('/courses', [Courses_Controller::class, 'index'])->name('Courses.Index');
Route::get('/courses/Form', function () {
    return view('admin.Coures_Form');
})->name('Courses.Form');

Route::post('/courses/Store', action: [Courses_Controller::class, 'Store'])->name('Course.Store');
Route::put('/courses/update/{uuid}', [Courses_Controller::class, 'update'])->name('Course.Update');
Route::delete('courses/delete/{uuid}', [Courses_Controller::class, 'destroy'])->name('courses.destroy');

Route::get('/Employee', [Employee_Controller::class, 'index'])->name('Employee.Index');
Route::post('/Employee/store', [Employee_Controller::class, 'store'])->name('Employee.Store');
Route::put('/Employee/update/{id}', [Employee_Controller::class, 'update'])->name('Employee.Update');
Route::delete('/Employee/{uuid}', [Employee_Controller::class, 'destroy'])->name('Employee.Destroy');

Route::get('/registrations', [Registration_Controller::class, 'index'])->name('registrations.index');

Route::get('/Dashboard', [DashboardController::class, 'index'])->name('Admin.Dashboard')->middleware(AuthSession::class);//});

// User routes
Route::get('/login', function () {
    return view('User.Login');
})->name('User.Login');

Route::get('/register', function () {
    return view('User.form_register');
})->name('User.register');

Route::post('/login/session', [Login_Controller::class, 'login'])->name('User.login.session');

Route::post('/register/submit', [Register_Controller::class, 'register'])->name('register.submit');

// ✅ Logout route
Route::post('/logout', function () {
    Auth::logout();
    session()->flush(); // ล้าง session ด้วย
    return redirect('/login');
})->name('logout');


Route::get('Forgotpassword', function () {
    return view('User.Forgot_password');
})->name('Forgot_password');

Route::post('/forgot-password', [ForgotPassword_Controller::class, 'resetPassword'])->name('User.Forgot.Submit');


Route::get('/Home', function () {
    return view('User.Home');
});

Route::get('/', function () {
    return view('User.index');
})->name('index');
Route::get('/home', function () {
    return view('User.home');
})->name('home');
Route::get('/contace', function () {
    return view('User.contace');
})->name('contace');
Route::get('/about', function () {
    return view('User.about');
})->name('about');
Route::get('/Courses/detail/{id}', [CoursesUser_controller::class, 'detail'])->name('courses.detail');

Route::get('/coursesUser', [CoursesUser_controller::class, 'index'])->name('user.show');
Route::get('/payment/{id}', [CoursesUser_controller::class, 'payment'])->name('user.payment');
Route::post('/free', [CoursesUser_controller::class, 'free'])->name('free');

//QRCODE
Route::get('/qrcode', [Payment::class, 'showQr'])->name('Qrcode');
Route::post('/api/verify-slip', [verify_slip::class, 'verifySlip'])->name('verify-slip');
Route::get('/video/{id}', [VideoController::class, 'stream'])->name('video.stream');
Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify.otp');

//UPLOAD
Route::post('/upload/video', [Courses_Controller::class, 'uploadVideo'])->name('upload.video');
Route::post('/video/update', [Courses_Controller::class, 'update_video'])->name('video.update');
