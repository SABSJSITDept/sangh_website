<?php

namespace App\Models\AppRegistration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationStatus extends Model
{
    use HasFactory;

    protected $table = 'registration_status';

    protected $fillable = [
        'status',
    ];

    public $timestamps = false;
}
