<?php

// app/Models/ShreeSangh/SanghPravartiya/Jsp/JspGujratiBooks.php
namespace App\Models\ShreeSangh\SanghPravartiya\Jsp;

use Illuminate\Database\Eloquent\Model;

class JspGujratiBooks extends Model
{
    protected $table = 'jsp_gujrati_books';

    protected $fillable = ['name', 'pdf', 'preference'];
}
