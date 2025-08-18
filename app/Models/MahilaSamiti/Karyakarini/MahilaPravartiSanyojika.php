<?php

namespace App\Models\MahilaSamiti\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaPravartiSanyojika extends Model
{
    use HasFactory;

    protected $table = 'mahila_pravarti_snyojaka';

    protected $fillable = [
        'name',
        'city',
        'post',
        'pravarti',
        'mobile',
        'photo',
    ];
}

