<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\EnumService;

class StoreAlmacenCarneRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var EnumService $enums */
        $enums = app(EnumService::class);

        $animales = $enums->values('animal_carne_tipo');

        return [
            'animal' => ['required', 'in:' . implode(',', $animales)],
            'pieza'  => ['required', 'string', 'max:255'],
        ];
    }
}