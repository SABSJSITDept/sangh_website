<?php

namespace App\Models\MahilaSamiti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaPst extends Model
{
    use HasFactory;

    protected $table = 'mahila_pst';

    protected $fillable = [
        'name', 'post', 'photo'
    ];
}
