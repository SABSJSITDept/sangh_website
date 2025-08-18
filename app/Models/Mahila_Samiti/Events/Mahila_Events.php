<?php

namespace App\Models\Mahila_Samiti\Events;

use Illuminate\Database\Eloquent\Model;

class Mahila_Events extends Model
{
    protected $table = 'mahila_events';

    protected $fillable = [
        'title',
        'content',
        'photo',
    ];
}
