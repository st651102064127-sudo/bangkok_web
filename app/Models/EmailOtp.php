<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailOtp extends Model
{
    use HasFactory;

    public $incrementing = false; // ปิด auto-increment
    protected $keyType = 'string'; // primary key เป็น string (UUID)

    protected $fillable = ['id', 'email', 'otp', 'expires_at'];
    protected $dates = ['expires_at'];

    protected static function boot()
    {
        parent::boot();

        // สร้าง UUID อัตโนมัติก่อน insert
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
      protected $casts = [
        'expires_at' => 'datetime', // แปลงเป็น Carbon
    ];
}
