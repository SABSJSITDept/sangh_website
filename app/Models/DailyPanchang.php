<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPanchang extends Model
{
    use HasFactory;

    protected $table = 'daily_panchangs';

    protected $fillable = [
        'date',
        'lunar_month_name',
        'vikram_samvat',
        'tithi_number',
        'tithi',
        'paksha',
        'tithi_two',
    ];

    protected $casts = [
        'date'         => 'date',
        'vikram_samvat' => 'integer',
        'tithi_number'  => 'integer',
    ];
}
