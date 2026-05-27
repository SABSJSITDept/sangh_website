<?php

namespace App\Models\MahilaSamiti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaPravartiya extends Model
{
    use HasFactory;

    protected $table = 'mahila_pravartiya';

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];
}
