<?php

namespace App\Models\SanghSahitya;

use Illuminate\Database\Eloquent\Model;

class Shramnopasak extends Model
{
    protected $table = 'shramnopasak';
    protected $fillable = ['year', 'month', 'cover_photo', 'pdf', 'file_type', 'drive_link'];
}
