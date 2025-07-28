<?php

// app/Models/ShreeSangh/Karyakarini/ItCell.php
namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class ItCell extends Model
{
    protected $table = 'it_cell';

    protected $fillable = ['name', 'post', 'city', 'mobile', 'photo'];
}
