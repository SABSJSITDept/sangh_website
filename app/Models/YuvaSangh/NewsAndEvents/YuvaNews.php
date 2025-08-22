<?php

namespace App\Models\YuvaSangh\NewsAndEvents;

use Illuminate\Database\Eloquent\Model;

class YuvaNews extends Model
{
    protected $table = 'yuva_news';

    protected $fillable = [
        'title', 'description', 'photo'
    ];
}
