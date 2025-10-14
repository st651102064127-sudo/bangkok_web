<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    public function stream($id)
    {
        // ID ของ Google Drive
        $googleDriveFileId = $id;

        // สร้าง URL download ของ Google Drive
        $url = "https://drive.google.com/uc?export=download&id={$googleDriveFileId}";

        // ส่ง request ไป Google Drive
        $response = Http::withOptions(['stream' => true])->get($url);

        // ส่ง header ให้ browser รู้ว่าเป็นวิดีโอ
        return response($response->body(), 200)
            ->header('Content-Type', 'video/mp4')
            ->header('Content-Disposition', 'inline');
    }
}
