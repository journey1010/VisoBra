<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class Fotos extends FormRequest
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
            'items' =>  'required|integer'
        ];
    }
}
