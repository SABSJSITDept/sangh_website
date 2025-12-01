<?php

namespace App\Models\spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfSafarnama extends Model
{
    use HasFactory;

    protected $table = 'spf_safarnama';

    protected $fillable = [
        'title',
        'pdf'
    ];
}
