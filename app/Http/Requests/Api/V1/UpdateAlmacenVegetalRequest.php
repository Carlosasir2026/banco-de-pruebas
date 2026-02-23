<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\EnumService;

class UpdateAlmacenVegetalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var EnumService $enums */
        $enums = app(EnumService::class);

        $categorias = $enums->values('categoria_vegetal_tipo');

        return [
            'categoria' => ['sometimes', 'in:' . implode(',', $categorias)],
            'nombre'    => ['sometimes', 'string', 'max:255'],
        ];
    }
}