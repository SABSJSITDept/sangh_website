<?php

namespace App\Models\Aanchal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aanchal extends Model
{
    use HasFactory;

    protected $table = 'anchal';

    protected $fillable = ['name'];
}
