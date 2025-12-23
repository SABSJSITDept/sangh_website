<?php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;

class PravartiSanyojak extends Model
{
    protected $table = 'pravarti_sanyojak';
    protected $fillable = ['name', 'post', 'city', 'pravarti_id', 'mobile', 'photo', 'session'];

    public function pravarti()
    {
        return $this->belongsTo(\App\Models\Pravarti\Pravarti::class, 'pravarti_id');
    }
}
