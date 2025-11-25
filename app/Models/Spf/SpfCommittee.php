<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfCommittee extends Model
{
    use HasFactory;

    protected $table = 'spf_committee';

    protected $fillable = [
        'name',
        'post',
        'anchal_id',
        'photo',
    ];

    public function anchal()
    {
        return $this->belongsTo(\App\Models\Aanchal\Aanchal::class);
    }
}
