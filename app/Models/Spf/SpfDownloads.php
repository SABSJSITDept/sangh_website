<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfDownloads extends Model
{
    use HasFactory;

    protected $table = 'spf_downloads';

    protected $fillable = [
        'title',
        'file'
    ];
}
