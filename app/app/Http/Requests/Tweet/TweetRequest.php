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
            'text'     => ['required', 'string', 'max:100'],
            'image' => 'required_without:picture_original|file|image:jpeg,png,jpg,gif|max:100000',
        ];
    }

    public function message()
    {
        return [
            'text.required' => '名前は必ず入力してね',
            'text.max' => '名前は必ず入力してね',
            'image.mines' => '指定のファイル形式以外は添付できません。',
        ];
    }
}
