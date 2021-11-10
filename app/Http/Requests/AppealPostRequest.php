<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Gender;

class AppealPostRequest extends FormRequest
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
    public static function rules()
    {
        return [
            'name' => 'required|string|max:20',
            'surname' => 'required|string|max:40',
            'patronymic' => 'nullable|string|max:20',
            'age' => 'required|integer|between:14, 116',
            'gender' => ['required', Rule::in([Gender::MALE, Gender::FEMALE])],
            'phone' => ['nullable','required_without:email','string', 'regex: /^(\+7|7|8){1}\ ?\(?[0-9]{3}\)?\ ?[0-9]{3}\ ?\-?\ ?[0-9]{2}\ ?\-?\ ?[0-9]{2}$/'],
            'email' => ['nullable','required_without:phone','string', 'email:rfc'],
            'message' => 'required|string|max:100',
        ];
    }
}
