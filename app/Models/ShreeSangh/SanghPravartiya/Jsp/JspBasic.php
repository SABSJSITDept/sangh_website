<?php

namespace App\Models\ShreeSangh\SanghPravartiya\Jsp;

use Illuminate\Database\Eloquent\Model;

class JspBasic extends Model
{
    protected $table = 'jsp_basic';

    protected $fillable = [
        'dtp',
        'content',
    ];
}
