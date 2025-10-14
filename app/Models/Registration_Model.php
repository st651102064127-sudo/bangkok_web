<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Courses_Model;
class Registration_Model extends Model
{
    protected $table = 'registrations'; // กำหนดชื่อตารางจริง
    protected $primaryKey = 'registration_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'registration_id',
        'course_id',
        'registration_date',
        'payment_status',
        'payment_amount',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'payment_amount' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Courses_Model::class);
    }
}
