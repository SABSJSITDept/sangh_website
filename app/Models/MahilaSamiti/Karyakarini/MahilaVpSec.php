<?php

namespace App\Models\MahilaSamiti\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaVpSec extends Model
{
    use HasFactory;

    protected $table = 'mahila_vp_sec';

    protected $fillable = [
        'name', 'post', 'city', 'mobile', 'aanchal_id', 'photo'
    ];

    public function aanchal()
    {
        return $this->belongsTo(\App\Models\Aanchal\Aanchal::class, 'aanchal_id');
    }
}
