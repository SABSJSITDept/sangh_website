<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfAbout extends Model
{
    use HasFactory;
    protected $table = 'spf_about';
    protected $fillable = ['content'];
}
