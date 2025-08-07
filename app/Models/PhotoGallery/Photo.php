<?php


namespace App\Models\PhotoGallery;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model {
    protected $table = 'photo_gallery';

    protected $fillable = [
        'category', 'event_name', 'photo',
    ];
}
