<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'price' => 'nullable|numeric|min:0',
            'valor' => 'nullable|numeric|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Para atualização, o ISBN pode ser único exceto para o próprio livro
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $bookId = $this->route('book');
            $rules['isbn'] = 'nullable|string|max:20|unique:books,isbn,' . $bookId;
        } else {
            $rules['isbn'] = 'nullable|string|max:20|unique:books,isbn';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'publication_year.required' => 'O ano de publicação é obrigatório.',
            'publication_year.integer' => 'O ano de publicação deve ser um número inteiro.',
            'publication_year.min' => 'O ano de publicação deve ser maior que 999.',
            'publication_year.max' => 'O ano de publicação não pode ser maior que o ano atual.',
            'isbn.max' => 'O ISBN não pode ter mais de 20 caracteres.',
            'isbn.unique' => 'Este ISBN já está em uso por outro livro.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.min' => 'O preço deve ser maior ou igual a zero.',
            'valor.numeric' => 'O valor deve ser um número.',
            'valor.min' => 'O valor deve ser maior ou igual a zero.',
            'authors.required' => 'Pelo menos um autor deve ser selecionado.',
            'authors.array' => 'Os autores devem ser fornecidos como uma lista.',
            'authors.min' => 'Pelo menos um autor deve ser selecionado.',
            'authors.*.exists' => 'Um ou mais autores selecionados não existem.',
            'subjects.required' => 'Pelo menos um assunto deve ser selecionado.',
            'subjects.array' => 'Os assuntos devem ser fornecidos como uma lista.',
            'subjects.min' => 'Pelo menos um assunto deve ser selecionado.',
            'subjects.*.exists' => 'Um ou mais assuntos selecionados não existem.',
            'cover_image.image' => 'O arquivo deve ser uma imagem.',
            'cover_image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'cover_image.max' => 'A imagem não pode ser maior que 2MB.',
        ];
    }
}
