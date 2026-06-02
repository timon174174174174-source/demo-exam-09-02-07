<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Приводим дату из формата ДД.ММ.ГГГГ к Y-m-d
     * (на случай ручного ввода без календаря flatpickr).
     */
    protected function prepareForValidation(): void
    {
        $date = $this->input('event_date');

        if (is_string($date) && preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $date)) {
            try {
                $this->merge([
                    'event_date' => Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d'),
                ]);
            } catch (\Throwable $e) {
                // оставляем как есть — валидатор сообщит об ошибке формата
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:rooms,id'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'payment_method' => ['required', Rule::in(Booking::PAYMENT_METHODS)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'room_id.required' => 'Выберите помещение.',
            'room_id.exists' => 'Выбрано несуществующее помещение.',
            'event_date.required' => 'Укажите дату начала конференции.',
            'event_date.date' => 'Укажите корректную дату в формате ДД.ММ.ГГГГ.',
            'event_date.after_or_equal' => 'Дата не может быть в прошлом.',
            'payment_method.required' => 'Выберите способ оплаты.',
            'payment_method.in' => 'Выбран недопустимый способ оплаты.',
        ];
    }
}
