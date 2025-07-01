<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'start_date',
        'end_date',
        'reduction',
        'total_price',
        'status',
        'note',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function isOverlapping($propertyId, $startDate, $endDate, $excludeId = null)
    {
        Log::debug('isOverlapping called', [
            'property_id' => $propertyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'exclude_id' => $excludeId,
        ]);

        $query = self::where('property_id', $propertyId)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->where(function ($query) use ($startDate, $endDate) {
            $query->where('start_date', '<', $endDate)
                ->where('end_date', '>', $startDate);
        });

        $exists = $query->exists();

        Log::debug('isOverlapping result', ['exists' => $exists]);

        return $exists;
    }


}
