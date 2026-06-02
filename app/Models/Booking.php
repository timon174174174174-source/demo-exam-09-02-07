<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'room_id', 'event_date', 'payment_method', 'status'])]
class Booking extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_COMPLETED = 'completed';

    /** Список статусов: код => подпись. */
    public const STATUSES = [
        self::STATUS_NEW => 'Новая',
        self::STATUS_ASSIGNED => 'Мероприятие назначено',
        self::STATUS_COMPLETED => 'Мероприятие завершено',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /** Человекочитаемая подпись статуса. */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** Отзыв можно оставить только после согласования администратором и один раз. */
    public function canBeReviewed(): bool
    {
        return $this->status !== self::STATUS_NEW && ! $this->review()->exists();
    }
}
