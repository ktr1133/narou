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
            'point_from'  => 'numeric',
            'point_to'    => 'numeric',
            'gan_from'    => 'numeric',
            'gan_to'      => 'numeric',
            'unique_from' => 'numeric',
            'unique_to'   => 'numeric',
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
