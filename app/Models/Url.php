<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'hashed_url',
        'long_url',
        'click_counts',
        'user_id',
        'single_use',
        'ownership_type',
        'active'
    ];

    public static function hashUrl($longUrl)
    {
        return substr(hash('sha256', $longUrl),0,10);
    }
}
