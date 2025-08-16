<?php

namespace App\Models\SanghSahitya;

use Illuminate\Database\Eloquent\Model;

class Pakhi extends Model
{
    protected $table = 'pakhi_ka_panna';

    protected $fillable = [
        'year',
        'pdf',
    ];
}
