<?php

namespace App\Http\Requests\Api\V1;

use App\Services\EnumService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnimalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $enums = app(EnumService::class);

        $especies  = $enums->values('especie_tipo');
        $actividades = $enums->values('actividad_tipo');
        $apetitos  = $enums->values('apetito_tipo');

        return [
            'nombre' => ['sometimes', 'string', 'max:255'],
            'especie' => ['sometimes', Rule::in($especies)],
            'raza' => ['sometimes', 'nullable', 'string', 'max:255'],
            'nacimiento_ano' => ['sometimes', 'nullable', 'integer', 'min:1900', 'max:2100'],
            'nacimiento_mes' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:12'],
            'peso_kg' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'objetivo_kg' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'actividad' => ['sometimes', 'nullable', Rule::in($actividades)],
            'apetito' => ['sometimes', 'nullable', Rule::in($apetitos)],
            'esterilizado' => ['sometimes', 'nullable', 'boolean'],
            'heces' => ['sometimes', 'nullable', 'string'],
            'alergias_detalle' => ['sometimes', 'nullable', 'string'],
            'patologias' => ['sometimes', 'nullable', 'array'],
            'patologias.*' => ['string', 'max:120'],
        ];
    }
}