<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class ItCellPdf extends Model
{
    protected $table = 'it_cell_pdf';

    protected $fillable = [
        'name',
        'session',
        'pdf'
    ];
}
