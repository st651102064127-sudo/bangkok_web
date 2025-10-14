<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_Model extends Model
{
    use HasFactory;

    protected $table = 'users'; // ใช้ตาราง users

    protected $primaryKey = 'uuid';
    protected $casts = [
        'uuid' => 'string',
    ];
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'fullname',
        'email',
        'username',
        'tel',
        'password',
        'status',
    ];
}
