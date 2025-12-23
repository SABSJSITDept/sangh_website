<?php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class VpSec extends Model
{
    protected $table = 'vp_sec';

    protected $fillable = [
        'name',
        'post',
        'city',
        'mobile',
        'aanchal',
        'photo',
        'session'
    ];
}
