<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use illuminate\Http\Exceptions\HttpResponseException;

class searchTotals extends FormRequest
{

    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $json = new JsonResponse([
            'message' => messageValidation($validator),
        ], 422);
        throw new Exception($json);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departamento' => 'nullable|string',
            'provincia' => 'nullable|string',
            'distrito' => 'nullable|string',
            'nivelGobierno' => 'nullable|string|in:GR,GL,GN'
        ];
    }

    public function messages(): array
    {
        return [
            'departamento.string' => 'Departamento es textual',
            'provincia.string' => 'Provincia es textual',
            'distrito.string' => 'Distrito es textual',
            'nivelGobierno.in' =>'Nivel de gobierno solo admite: GR, GL, GN'
        ];
    }
}
