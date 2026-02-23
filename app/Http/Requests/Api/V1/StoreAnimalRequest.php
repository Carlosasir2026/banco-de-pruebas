<?php

namespace App\Http\Requests\Api\V1;

use App\Services\EnumService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $enums = app(EnumService::class);

        $especies  = $enums->values('especie_tipo');
        $actividades = $enums->values('actividad_tipo');
        $apetitos  = $enums->values('apetito_tipo');

        return [
            'id_user' => ['required', 'integer'],

            'nombre' => ['required', 'string', 'max:255'],
            'especie' => ['required', Rule::in($especies)],

            'raza' => ['nullable', 'string', 'max:255'],

            'nacimiento_ano' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'nacimiento_mes' => ['nullable', 'integer', 'min:1', 'max:12'],

            'peso_kg' => ['nullable', 'numeric', 'min:0'],
            'objetivo_kg' => ['nullable', 'numeric', 'min:0'],

            'actividad' => ['nullable', Rule::in($actividades)],
            'apetito' => ['nullable', Rule::in($apetitos)],

            'esterilizado' => ['nullable', 'boolean'],

            'heces' => ['nullable', 'string'],
            'alergias_detalle' => ['nullable', 'string'],

            'patologias' => ['nullable', 'array'],
            'patologias.*' => ['string', 'max:120'],
        ];
    }
}