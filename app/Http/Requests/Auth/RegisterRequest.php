<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'min:6', 'regex:/^[A-Za-z0-9]+$/', 'unique:users,login'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Укажите логин.',
            'login.min' => 'Логин должен быть не короче 6 символов.',
            'login.regex' => 'Логин может содержать только латинские буквы и цифры.',
            'login.unique' => 'Этот логин уже занят, выберите другой.',
            'full_name.required' => 'Укажите ФИО.',
            'phone.required' => 'Укажите контактный номер телефона.',
            'email.required' => 'Укажите e-mail.',
            'email.email' => 'Введите корректный e-mail.',
            'password.required' => 'Придумайте пароль.',
            'password.min' => 'Пароль должен быть не короче 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ];
    }
}
