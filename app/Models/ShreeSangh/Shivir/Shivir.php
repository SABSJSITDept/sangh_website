<?php
// app/Models/ShreeSangh/Shivir/Shivir.php

namespace App\Models\ShreeSangh\Shivir;

use Illuminate\Database\Eloquent\Model;

class Shivir extends Model
{
    protected $table = 'shivir';

    protected $fillable = [
        'title', 'date', 'location', 'description', 'photo'
    ];
}
