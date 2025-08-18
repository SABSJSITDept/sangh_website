<?php

namespace App\Models\MahilaSamiti\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class MahilaExPrsident extends Model
{
    protected $table = 'mahila_ex_prsident';

    protected $fillable = [
        'name',
        'karyakal',
        'place',
        'photo'
    ];
}
