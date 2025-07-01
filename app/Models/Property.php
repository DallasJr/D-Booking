<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'city',
        'price_per_night',
        'image',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected static function booted()
    {
        static::deleting(function ($property) {
            if ($property->image && Storage::disk('public')->exists($property->image)) {
                Storage::disk('public')->delete($property->image);
            }

            $property->bookings()->update(['status' => 'property_removed']);
        });

        static::updating(function ($property) {
            if ($property->isDirty('image')) {
                $oldImage = $property->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}
