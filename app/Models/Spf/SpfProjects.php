<?php

namespace App\Models\spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfProjects extends Model
{
    use HasFactory;

    protected $table = 'spf_projects';

    protected $fillable = [
        'title',
        'description',
    ];
}
