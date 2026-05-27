<?php

namespace App\Models\MahilaSamiti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaDescription extends Model
{
    use HasFactory;

    protected $table = 'mahila_description';

    protected $fillable = [
        'description'
    ];
}
