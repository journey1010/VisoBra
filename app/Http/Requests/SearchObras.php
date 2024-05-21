<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SearchObras extends FormRequest
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
            'codeUnique' => 'nullable|number',
            'snip' => 'nullable|number',
            'nombreObra' => 'nullable|string|max:400',
            'provincia' => 'nullable|string',
            'nivelGobierno' => 'nullable|string|in:GR,GL,GN',
            'distrito ' => 'nullable|string',
            'page' => 'nullable|number',
            'itemsPerPage' => 'nullable|int',
            'estadoInversion' => 'nullable|string',
            'funcion' => 'nullable|string',
            'subprograma' => 'nullable|string',
            'programa' =>  'nullable|string',
            'sector' => 'nullable|string',
        ];
    }

    public function messages()
    {
       return [
            'codeUnique.number' => 'Código unico de inversión es un númerico',
            'snip.number' => 'Código snip es númerico',
            'nombreObra.string' => 'Proyecto de inversión es textual',
            'nombreObra.max' => 'Has superado el limite de 400 caracteres permitidos por el proyecto de inversión',
            'provincia.string' => 'Nombre de provincia es textual',
            'nivelGobierno.in' => 'Nivel de gobierno no existe',
            'distrito.string' => 'Distrito es textual',
            'page.number' => 'Número de página es numérico lol',
            'itemsPerPage' => 'Númerico de items es numérico lol', 
            'estadoInversion.string' => 'nullable|string',
            'funcion.string' => 'Función es textual',
            'subprograma.string' => 'Subprograma es textual',
            'programa.string' =>  'Programa es textual',
            'sector.string' => 'Sector es textual',
       ];
    }
}