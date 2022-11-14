<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // SETANDO AS REGRAS DOS PRODUTOS
        return [
            'name' => ['required', Rule::unique('products')->ignore(app('request')->segment(3))],
            'price' => ['required'],
            'description' => ['required'],
            'category' => ['required'],
        ];
    }

    public function messages()
    {
        //TRADUZINDO AS MENSAGENS DE ERRO
        return [
            'name.required'    => 'O nome do produto é obrigatório.',
            'name.unique'    => 'Já existe um produto com este nome em nossos registros.',
            'price.required'    => 'O preço do produto é obrigatório.',
            'description.required'    => 'A descrição do produto é obrigatória.',
            'category.required'    => 'A categoria do produto é obrigatória.',
        ];
    }
}
