<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfEvents extends Model
{
    use HasFactory;

    protected $table = 'spf_events';

    protected $fillable = [
        'title',
        'date',
        'time',
        'location',
        'description',
        'photo',
    ];
}
