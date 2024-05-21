<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SearchObras extends FormRequest
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
            'codeUnique' => 'nullable|number',
            'snip' => 'nullable|number',
            'nombreObra' => 'nullable|string|max:400',
            'provincia' => 'nullable|string',
            'nivelGobierno' => 'nullable|string|in:GR,GL,GN',
            'distrito ' => 'nullable|string',
            'page' => 'nullable|number',
            'itemsPerPage' => 'nullable|int',
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
       ];
    }
}
