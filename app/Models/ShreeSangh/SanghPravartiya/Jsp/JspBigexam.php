<?php

namespace App\Models\ShreeSangh\SanghPravartiya\Jsp;

use Illuminate\Database\Eloquent\Model;

class JspBigexam extends Model
{
    protected $table = 'jsp_bigexam';
    protected $fillable = ['name', 'pdf', 'priority'];
}

