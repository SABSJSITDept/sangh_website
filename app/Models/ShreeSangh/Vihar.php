<?php

// app/Models/ShreeSangh/Vihar.php

namespace App\Models\ShreeSangh;

use Illuminate\Database\Eloquent\Model;

class Vihar extends Model
{
    protected $table = 'vihar';

    protected $fillable = [
        'location',
    ];
}

