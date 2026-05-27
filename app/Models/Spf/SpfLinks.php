<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfLinks extends Model
{
    use HasFactory;

    protected $table = 'spf_links';

    protected $fillable = [
        'mobile_number',
        'email',
        'whatsapp_number',
        'website_link',
        'registration_link',
        'facebook_link',
        'instagram_link',
        'youtube_link',
        'twitter_link'
    ];

    protected $casts = [
        'mobile_number' => 'array',
        'whatsapp_number' => 'array',
        'email' => 'array',
    ];
}
