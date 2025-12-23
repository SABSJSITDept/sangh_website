<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class SanyojanMandalPdf extends Model
{
    protected $table = 'sanyojan_mandal_pdf';

    protected $fillable = ['name', 'session', 'pdf'];
}
