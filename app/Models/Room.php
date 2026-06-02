<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'type', 'description', 'capacity', 'image'])]
class Room extends Model
{
    /** Заявки на это помещение. */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
