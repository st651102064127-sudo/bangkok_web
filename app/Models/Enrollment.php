<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';
    protected $primaryKey = 'enroll_id';

    protected $fillable = [
        'course_id',
        'user_id',
        'payment_id',
        'payment_amount',
        'status',
        'ref',
    ];

    // Accessor สำหรับวันที่ลงทะเบียน
    public function getRegistrationDateAttribute() {
        return $this->created_at;
    }

    // Relation กับ Course
    public function course() {
        return $this->belongsTo(Courses_Model::class, 'course_id', 'id');
    }

    // Relation กับ User
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
