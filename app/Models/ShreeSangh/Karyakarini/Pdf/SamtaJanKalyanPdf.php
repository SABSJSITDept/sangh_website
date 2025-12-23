<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class SamtaJanKalyanPdf extends Model
{
    protected $table = 'samta_jan_kalyan_pdf';
    protected $fillable = ['name', 'session', 'pdf'];
}
