<?php

namespace App\Models\Spf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfEvents extends Model
{
    use HasFactory;

    protected $table = 'spf_events';

    protected $fillable = [
        'title',
        'date',
        'time',
        'location',
        'description',
        'photo',
        'spf_project_id',
        'event_reg_start',
        'event_reg_close',
        'home_page',
    ];

    /**
     * Get the project associated with this event.
     */
    public function project()
    {
        return $this->belongsTo(SpfProjects::class, 'spf_project_id');
    }
}
