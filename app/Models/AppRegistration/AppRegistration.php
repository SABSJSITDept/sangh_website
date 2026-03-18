<?php

namespace App\Models\AppRegistration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppRegistration extends Model
{
    use HasFactory;

    protected $table = 'mrm_app';

    protected $fillable = [
        'first_name',
        'last_name',
        'relation_id',
        'guardian_name',
        'gender',
        'birth_day',
        'education',
        'occupation',
        'address_type',
        'address',
        'address2',
        'post',
        'city',
        'district',
        'pincode',
        'country',
        'state',
        'mobile',
        'whatsapp_number',
        'alternate_number',
        'email_address',
        'adhar_name',
        'adharfatherName',
        'adhar1',
        'adhar2',
        'adhar3',
        'marital_status',
        'rel_faith',
        'member_id',
        'family_id',
        'app_status',
        'registration',
    ];

    protected $casts = [
        'birth_day' => 'date',
        'app_status' => 'integer',
        'registration' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set registration to 1 by default
            $model->registration = 1;
        });

        static::created(function ($model) {
            // Update member_id and family_id after creation
            $model->member_id = $model->id + 1500000;
            $model->family_id = $model->id + 1000000;
            $model->saveQuietly();
        });
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
