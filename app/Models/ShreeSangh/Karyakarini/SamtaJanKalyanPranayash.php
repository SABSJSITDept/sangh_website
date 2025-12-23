<?php

// app/Models/ShreeSangh/Karyakarini/SamtaJanKalyanPranayash.php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamtaJanKalyanPranayash extends Model
{
    use HasFactory;

    protected $table = 'samta_jan_kalyan_pranayash';

    protected $fillable = ['name', 'city', 'mobile', 'photo', 'session'];
}
