<?php

namespace App\Models\ShreeSangh\AavedanPatra;

use Illuminate\Database\Eloquent\Model;

class AavedanPatra extends Model
{
    protected $table = 'aavedan_patra';
    protected $fillable = ['name', 'file','file_type', 'category', 'preference'];
}
