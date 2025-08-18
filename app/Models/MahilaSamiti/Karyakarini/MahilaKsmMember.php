<?php

namespace App\Models\MahilaSamiti\Karyakarini;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aanchal\Aanchal;

class MahilaKsmMember extends Model
{
    use HasFactory;

    protected $table = 'mahila_ksm_members';

    protected $fillable = [
        'name',
        'city',
        'mobile',
        'aanchal_id',
        'photo',
    ];

    public function aanchal()
    {
        return $this->belongsTo(Aanchal::class, 'aanchal_id');
    }
}
