<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ncode'  => 'required|string|size:7',
            'writer' => 'string',
        ];
    }

    public function attributes()
    {
        return [
            'ncode'  => 'ncode',
            'writer' => '作者',
        ];        
    }

    public function message()
    {
        return [
            'ncode'  => ':attributeは7桁の英数字です',
            'writer' => ':attributeが正確に入力されませんでした。',
        ];
    }
}
