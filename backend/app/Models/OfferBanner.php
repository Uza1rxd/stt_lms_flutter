<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OfferBanner extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function thumbnail(): Attribute
    {
        $media = asset('media/demoimage.png');

        if ($this->media && Storage::exists($this->media->src)) {
            $media = Storage::url($this->media->src);
        }

        return Attribute::make(
            get: fn() => $media,
        );
    }
}
