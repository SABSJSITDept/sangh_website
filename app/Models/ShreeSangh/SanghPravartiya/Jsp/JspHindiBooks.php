<?php

namespace App\Models\ShreeSangh\SanghPravartiya\Jsp;

use Illuminate\Database\Eloquent\Model;

class JspHindiBooks extends Model
{
    protected $table = 'jsp_hindi_books';

    protected $fillable = ['name', 'pdf'];
}
