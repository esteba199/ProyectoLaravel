<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Usamos 'correo' y 'clave' porque así están en tu base de datos
        return [
            'correo' => ['required', 'string', 'email'],
            'clave' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        // Intentamos entrar usando tus columnas personalizadas
        if (! Auth::attempt(['correo' => $this->correo, 'password' => $this->clave], $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'correo' => __('auth.failed'),
            ]);
        }
    }
}