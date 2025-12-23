<?php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class SthayiSampatiSanwardhanSamiti extends Model
{
    protected $table = 'sthayi_sampati_sanwardhan_samiti';

    protected $fillable = [
        'name',
        'post',
        'city',
        'mobile_number',
        'photo',
        'session'
    ];
}
