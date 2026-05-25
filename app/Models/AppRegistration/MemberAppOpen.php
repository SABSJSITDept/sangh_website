<?php

namespace App\Models\AppRegistration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberAppOpen extends Model
{
    use HasFactory;

    protected $table = 'member_app_opens';

    protected $fillable = [
        'member_id',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the member that opened the app.
     */
    public function member()
    {
        return $this->belongsTo(AppRegistration::class, 'member_id', 'member_id');
    }
}
