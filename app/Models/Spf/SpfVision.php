<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfVision extends Model
{
    use HasFactory;
    protected $table = 'spf_vision';
    protected $fillable = ['content'];
}
