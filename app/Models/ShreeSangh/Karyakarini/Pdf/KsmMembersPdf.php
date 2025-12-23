<?php

namespace App\Models\ShreeSangh\Karyakarini\Pdf;

use Illuminate\Database\Eloquent\Model;

class KsmMembersPdf extends Model
{
    protected $table = 'ksm_members_pdf';
    protected $fillable = ['name', 'session', 'pdf'];
}
