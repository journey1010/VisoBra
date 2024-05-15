<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;


class Horizon extends FormRequest
{

    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     */

    protected function failedValidation(Validator $validator)
    {
        $jsonResponse = new JsonResponse([
            'status' => 'error',
            'message' => messageValidation($validator)
        ]); 
        throw new HttpResponseException($jsonResponse);
    }

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
            'email' => 'required|string|email',
            'password' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Ingrese un correo electronico',
            'email.email' => 'No es un correo',
            'password.required' => 'Ingrese una contraseña'
        ];
    }
}
