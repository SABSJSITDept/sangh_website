<?php

namespace App\Models\YuvaSangh\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YuvaPst extends Model
{
    use HasFactory;

    protected $table = 'yuva_pst';

    protected $fillable = [
        'name',
        'post',
        'photo',
    ];
}
