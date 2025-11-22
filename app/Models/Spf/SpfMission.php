<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfMission extends Model
{
    use HasFactory;
    protected $table = 'spf_mission';
    protected $fillable = ['content'];
}
