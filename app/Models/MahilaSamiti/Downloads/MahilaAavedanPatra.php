<?php

namespace App\Models\MahilaSamiti\Downloads;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahilaAavedanPatra extends Model
{
    use HasFactory;

    protected $table = 'mahila_aavedan_patra';

    protected $fillable = [
        'name',
        'type',
        'pdf',
        'google_form_link',
    ];
}
