<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Courses_Model;
class Syllabus extends Model
{
    use HasFactory;
    protected $table = "Syllabuses";
    protected $primaryKey = "syllabus_id";
    protected $fillable = [
        'title',
        'duration',
        'course_id',
        'order',
        'video_url'
    ];
    // กำหนดความสัมพันธ์แบบ Many-to-One กับ Course
    public function course()
    {
        return $this->belongsTo(Courses_Model::class);
    }

}
