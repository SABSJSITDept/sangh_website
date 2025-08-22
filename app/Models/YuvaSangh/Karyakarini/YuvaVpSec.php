<?php

namespace App\Models\YuvaSangh\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YuvaVpSec extends Model
{
    use HasFactory;

    protected $table = 'yuva_vp_sec';

    protected $fillable = [
        'name',
        'post',
        'city',
        'aanchal',
        'mobile',
        'photo',
    ];
}
