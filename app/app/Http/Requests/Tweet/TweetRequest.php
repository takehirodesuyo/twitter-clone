<?php

namespace App\Http\Requests\Tweet;

use Illuminate\Foundation\Http\FormRequest;

class TweetRequest extends FormRequest
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
        return [
            'text'     => ['required', 'string', 'max:10'],
            'img'    => ['file', 'mimes:jpg,png'],
        ];
    }

    public function messages()
    {
        return [
            'text.required' => '必須項目',
            'text.max' => '100文字以内',
            'img.mimes' => 'jpg,png 指定のファイル形式以外は添付できません。',
        ];
    }
}
