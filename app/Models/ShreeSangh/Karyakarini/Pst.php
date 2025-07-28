<?php

// app/Models/ShreeSangh/Karyakarini/Pst.php
namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class Pst extends Model
{
    protected $table = 'pst';
    protected $fillable = ['name', 'post', 'photo'];
}

