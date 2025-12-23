<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class PravartiSanyojakPdf extends Model
{
    protected $table = 'pravarti_sanyojak_pdf';

    protected $fillable = [
        'name',
        'session',
        'pdf'
    ];
}
