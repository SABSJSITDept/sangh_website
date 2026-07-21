<?php

namespace App\Models\SanghSahitya\Shramnopasak\news_comments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComments extends Model
{
    use HasFactory;

    protected $table = 'news_comments';

    protected $fillable = [
        'news_id',
        'mid',
        'comment',
    ];
}
