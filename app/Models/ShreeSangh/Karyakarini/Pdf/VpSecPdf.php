<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class VpSecPdf extends Model
{
    protected $table = 'vp_sec_pdf';

    protected $fillable = [
        'name',
        'session',
        'pdf'
    ];
}
