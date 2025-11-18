<?php

namespace App\Models\ShreeSangh\SanghPravartiya\Jsp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JspResult extends Model
{
    use HasFactory;

    protected $table = 'jsp_result';

    protected $fillable = [
        'Student_Name',
        'Guardian_Name',
        'Mobile',
        'City',
        'State',
        'Class',
        'Marks',
        'Rank',
        'Remarks',
    ];
}
