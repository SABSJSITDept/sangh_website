<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfSlider extends Model
{
    use HasFactory;

    protected $table = 'spf_slider';

    protected $fillable = [
        'image',
    ];
}
