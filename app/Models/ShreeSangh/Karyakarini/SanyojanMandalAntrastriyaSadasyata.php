<?php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class SanyojanMandalAntrastriyaSadasyata extends Model
{
    protected $table = 'sanyojan_mandal_antrastriya_sadasyata';
    protected $fillable = ['name', 'city', 'mobile', 'photo', 'session'];
}
