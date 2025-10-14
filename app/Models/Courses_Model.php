<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ตรวจสอบให้แน่ใจว่าได้เรียกใช้ Model อื่นๆ ที่เกี่ยวข้อง
use App\Models\Syllabus;
use App\Models\CourseFeature;

class Courses_Model extends Model
{
    use HasFactory;

    protected $table = 'Courses';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'title',
        'category',
        'instructor',
        'duration',
        'level',
        'price',
        'image_url',
        'description',
    ];

    public function syllabuses()
    {
        // กำหนด foreign key ที่ถูกต้อง
        return $this->hasMany(Syllabus::class, 'course_id');
    }

    public function features()
    {
        // กำหนด foreign key ที่ถูกต้อง
        return $this->hasMany(CourseFeature::class, 'course_id');
    }
}
