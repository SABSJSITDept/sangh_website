<?php

namespace App\Models\ShreeSangh\Karyakarini;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aanchal\Aanchal;

class KaryasamitiSadasya extends Model
{
    protected $fillable = ['name', 'city', 'aanchal_id', 'mobile', 'photo'];

    public function aanchal()
    {
        return $this->belongsTo(Aanchal::class); // ✅ Correct
    }
}
