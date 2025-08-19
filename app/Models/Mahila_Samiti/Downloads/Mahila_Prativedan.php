<?php

namespace App\Models\Mahila_Samiti\Downloads;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahila_Prativedan extends Model
{
    use HasFactory;

    protected $table = 'mahila_prativedan';

    protected $fillable = [
        'name',
        'google_drive_link',
    ];
}
