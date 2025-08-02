<?php

namespace App\Models\ShreeSangh\SanghPravartiya\Jsp;

use Illuminate\Database\Eloquent\Model;

class JspExam extends Model
{
    protected $table = 'jsp_exam';
    protected $fillable = ['name', 'pdf', 'google_form_link'];
}

