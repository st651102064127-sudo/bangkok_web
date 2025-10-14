<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Login_Model extends Model
{
    // ชื่อตารางในฐานข้อมูล
    protected $table = 'account';

    // Primary key ของตาราง (ถ้าไม่ใช่ id)
    protected $primaryKey = 'email_account';

    // ถ้า Primary key ไม่ใช่ auto-increment
    public $incrementing = false;

    // ถ้า Primary key ไม่ใช่ integer
    protected $keyType = 'string';

    // ปิด timestamps (created_at, updated_at)
    public $timestamps = false;

    // ฟิลด์ที่สามารถกรอกข้อมูลได้ (mass assignable)
    protected $fillable = [
        'email_account',
        'password_account',
        'login_count_account',
        'lock_account',
        'ban_account'
    ];

    // ฟังก์ชันช่วยตรวจว่า account ถูก ban ชั่วคราวอยู่หรือไม่
    public function isBanned()
    {
        return $this->lock_account == 1 && $this->ban_account && Carbon::now()->lt(Carbon::parse($this->ban_account));
    }

    // ฟังก์ชัน reset count
    public function resetLoginAttempts()
    {
        $this->login_count_account = 0;
        $this->save();
    }

    // ฟังก์ชันเพิ่ม count
    public function incrementLoginAttempts()
    {
        $this->login_count_account += 1;
        $this->save();
    }

    // ฟังก์ชันล็อกบัญชี
    public function lock($minutes = 1)
    {
        $this->lock_account = 1;
        $this->ban_account = now()->addMinutes($minutes);
        $this->save();
    }
}
