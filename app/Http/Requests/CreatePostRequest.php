<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'point_from'  => 'nullable, numeric',
            'point_to'    => 'nullable, numeric',
            'gan_from'    => 'nullable, numeric',
            'gan_to'      => 'nullable, numeric',
            'unique_from' => 'nullable, numeric',
            'unique_to'   => 'nullable, numeric',
        ];
    }

    public function errors()
    {
        return [
            'required' => 'この条件は必須です。',
            'numeric' => '入力値が無効です。半角の数値のみを入力してください。',
        ];
    }

}
