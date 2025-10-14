<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeature extends Model
{
    use HasFactory;
    protected $table = "CourseFeatures";
    protected $fillable = [
        'feature_name',
        'feature_value',
        'course_id' // เพิ่ม 'course_id' เพื่อให้สามารถบันทึกได้
    ];
    // กำหนดความสัมพันธ์แบบ Many-to-One กับ Course
    public function course()
    {
        return $this->belongsTo(Courses_Model::class);
    }
}
