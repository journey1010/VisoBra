<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class SearchTotals extends FormRequest
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
        throw new HttpResponseException($json);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departamento' => 'nullable|boolean',
            'provincia' => 'nullable|boolean',
            'distrito' => 'nullable|boolean',
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
