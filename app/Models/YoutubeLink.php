<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeLink extends Model
{
    use HasFactory;

    protected $table = 'youtube_links';

    protected $fillable = [
        'judul',
        'youtube_url',
        'youtube_id',
        'kategori',
        'deskripsi',
        'urutan',
    ];

    /**
     * Helper untuk mengekstrak YouTube Video ID dari berbagai format URL YouTube
     */
    public static function parseYoutubeId($url)
    {
        if (empty($url)) return '';

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches)) {
            return $matches[1];
        }

        $clean = trim($url);
        if (strlen($clean) === 11) {
            return $clean;
        }

        return $clean;
    }
}
