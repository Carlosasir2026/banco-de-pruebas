<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\EnumService;

class StoreAlmacenVegetalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var EnumService $enums */
        $enums = app(EnumService::class);

        $categorias = $enums->values('categoria_vegetal_tipo');

        return [
            'categoria' => ['required', 'in:' . implode(',', $categorias)],
            'nombre'    => ['required', 'string', 'max:255'],
        ];
    }
}