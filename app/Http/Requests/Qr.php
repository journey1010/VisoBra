<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class Qr extends FormRequest
{
    protected $stopOnFirstFailure = true;


    protected function failedValidation(Validator $validator)
    {
        $jsonResponse = new JsonResponse([
            'message' => messageValidation($validator)
        ], 422); 
        throw new HttpResponseException($jsonResponse);
    }

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
            'cui' => 'required|integer|exists:obras,codigo_unico_inversion'
        ];
    }

    public function messages()
    {
        return[
            'cui.exists' => 'CUI proporcionado no existe'
        ];
    }
}
