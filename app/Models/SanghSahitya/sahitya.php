<?php

namespace App\Models\SanghSahitya;

use Illuminate\Database\Eloquent\Model;

class Sahitya extends Model
{
    protected $table = 'sahitya';

    protected $fillable = [
        'category',
        'name',
        'cover_photo',
        'pdf',
        'preference',
        'show_on_homepage',
    ];
}
