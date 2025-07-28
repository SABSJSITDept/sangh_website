<?php

// app/Models/ShreeSangh/News/News.php
namespace App\Models\ShreeSangh\News;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['title', 'date', 'time', 'location', 'description', 'photo'];
}
