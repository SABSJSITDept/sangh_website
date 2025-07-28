<?php

namespace App\Models\ShreeSangh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyThought extends Model
{
    use HasFactory;

    protected $fillable = [
        'thought',
        'date',
    ];
}
