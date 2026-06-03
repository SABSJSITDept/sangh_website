<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanghOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'google_link',
        'phone_numbers',
        'emails',
        'sequence',
    ];

    protected $casts = [
        'phone_numbers' => 'array',
        'emails' => 'array',
        'sequence' => 'integer',
    ];
}
