<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'dni' => ['required','string','max:50'],
            'nombre' => ['required','string','max:255'],
            'apellido_1' => ['nullable','string','max:255'],
            'apellido_2' => ['nullable','string','max:255'],
            'email' => ['required','email','max:255'],
            'password' => ['required','string','min:6','max:255'],
        ];
    }
}