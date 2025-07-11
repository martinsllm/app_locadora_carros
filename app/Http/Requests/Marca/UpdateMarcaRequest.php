<?php

namespace App\Http\Requests\Marca;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarcaRequest extends FormRequest
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
            'nome' => 'sometimes|min:3|unique:marcas,nome,' . $this->route('marca'),
            'imagem' => 'sometimes|file|mimes:png,jpg,jpeg'
        ];
    }
}
