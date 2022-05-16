<?php

namespace App\Http\Requests\Register;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            'token' => ['required', 'string'],
            'profile_image' => ['required', 'mimes:jpg,png']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '必須項目',
            'name.min' => '短すぎます。(3文字以上)',
            'profile_image.required' => '写真を選択してください',
            'profile_image.mimes' => 'jpg,png形式のみです',
        ];
    }
}
