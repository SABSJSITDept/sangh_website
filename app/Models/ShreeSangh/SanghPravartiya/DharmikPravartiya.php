<?php

namespace App\Models\ShreeSangh\SanghPravartiya;

use Illuminate\Database\Eloquent\Model;

class DharmikPravartiya extends Model
{
    protected $table = 'dharmik_pravartiya';

    protected $fillable = ['heading', 'content'];
}
