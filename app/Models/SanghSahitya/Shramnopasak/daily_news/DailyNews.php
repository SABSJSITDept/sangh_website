<?php

namespace App\Models\SanghSahitya\Shramnopasak\daily_news;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyNews extends Model
{
    use HasFactory;

    protected $table = 'daily_news';

    protected $fillable = [
        'title',
        'description',
        'anchal_id',
        'photo',
        'date',
        'city_id',
        'state_id',
        'local_sangh_id',
        'like_count',
    ];
}
