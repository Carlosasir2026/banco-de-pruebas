<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required','email','max:255'],
            'current_password' => ['required','string','max:255'],
            'new_password' => ['required','string','min:6','max:255'],
        ];
    }
}