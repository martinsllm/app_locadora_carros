<?php

namespace App\Http\Requests\Modelo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModeloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'sometimes|unique:modelos,nome,' . $this->route('modelo'),
            'imagem' => 'sometimes|file|mimes:png,jpg,jpeg',
            'numero_portas' => 'sometimes|integer|digits_between:1,5',
            'lugares' => 'sometimes|integer|digits_between:1,20',
            'air_bag' => 'sometimes|boolean',
            'abs' => 'sometimes|boolean',
        ];
    }
}
