<?php

namespace App\Models\Pravarti;

use Illuminate\Database\Eloquent\Model;

class Pravarti extends Model
{
    protected $table = 'pravarti';
    protected $primaryKey = 'id';

    protected $fillable = ['name'];
}
