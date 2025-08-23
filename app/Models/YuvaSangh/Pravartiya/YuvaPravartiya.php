<?php

namespace App\Models\YuvaSangh\Pravartiya;

use Illuminate\Database\Eloquent\Model;

class YuvaPravartiya extends Model
{
    protected $table = 'yuva_pravarti';

    protected $fillable = [
        'heading',
        'content',
        'photo', // store '/storage/yuva_pravartiya/...' (public disk)
    ];
}
