<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfEventReg extends Model
{
    use HasFactory;

    protected $table = 'spf_event_reg';

    protected $fillable = [
        'member_id',
        'event_id',
        'response',
    ];

    /**
     * Get the event that owns the registration
     */
    public function event()
    {
        return $this->belongsTo(SpfEvents::class, 'event_id');
    }
}
