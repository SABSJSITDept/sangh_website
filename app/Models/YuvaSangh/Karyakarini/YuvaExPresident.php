<?php

namespace App\Models\YuvaSangh\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YuvaExPresident extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'karyakal',
        'city',
        'photo',
    ];
}
