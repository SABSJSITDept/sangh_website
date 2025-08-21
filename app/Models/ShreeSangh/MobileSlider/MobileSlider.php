<?php

namespace App\Models\ShreeSangh\MobileSlider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileSlider extends Model
{
    use HasFactory;

    protected $table = 'mobile_slider';

    protected $fillable = [
        'image',
    ];
}
