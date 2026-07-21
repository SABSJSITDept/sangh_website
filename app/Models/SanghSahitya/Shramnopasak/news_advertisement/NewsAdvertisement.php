<?php

namespace App\Models\SanghSahitya\Shramnopasak\news_advertisement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsAdvertisement extends Model
{
    use HasFactory;

    protected $table = 'news_advertisement';

    protected $fillable = [
        'photo',
    ];
}
