<?php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class ExPresident extends Model
{
    protected $table = 'ek_president';

    protected $fillable = ['name', 'place', 'karaykal', 'photo'];
}
