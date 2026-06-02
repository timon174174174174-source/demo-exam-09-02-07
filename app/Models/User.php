<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['login', 'full_name', 'phone', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /** Является ли пользователь администратором. */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /** Заявки пользователя (свежие сверху). */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->latest();
    }

    /** Отзывы пользователя. */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
