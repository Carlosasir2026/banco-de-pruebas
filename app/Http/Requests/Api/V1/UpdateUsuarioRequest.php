<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'dni' => ['sometimes','nullable','string','max:50'],
            'nombre' => ['sometimes','string','max:255'],
            'apellido_1' => ['sometimes','nullable','string','max:255'],
            'apellido_2' => ['sometimes','nullable','string','max:255'],
            'email' => ['sometimes','email','max:255'],
        ];
    }
}