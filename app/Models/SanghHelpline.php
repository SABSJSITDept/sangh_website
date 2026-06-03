<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanghHelpline extends Model
{
    use HasFactory;

    protected $fillable = [
        'dept_name',
        'mobile_number',
        'email',
        'whatsapp_number',
        'is_only_whatsapp',
        'sequence',
    ];

    protected $casts = [
        'mobile_number' => 'array',
        'email' => 'array',
        'is_only_whatsapp' => 'boolean',
        'sequence' => 'integer',
    ];
}
