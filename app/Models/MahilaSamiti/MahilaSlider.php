<?php

namespace App\Models\MahilaSamiti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaSlider extends Model
{
    use HasFactory;

    protected $table = 'mahila_slider';

    protected $fillable = ['photo'];
}
