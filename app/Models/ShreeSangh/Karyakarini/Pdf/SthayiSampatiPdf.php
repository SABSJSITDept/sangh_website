<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class SthayiSampatiPdf extends Model
{
    protected $table = 'sthayi_sampati_pdf';

    protected $fillable = [
        'name',
        'session',
        'pdf'
    ];
}
