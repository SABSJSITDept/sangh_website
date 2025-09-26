<?php

namespace App\Models\YuvaSangh\GeneralUpdates;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'yuva_content'; // table name

    protected $fillable = ['content']; // fillable columns
}
