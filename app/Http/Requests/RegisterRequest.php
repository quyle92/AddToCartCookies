<?php

namespace App\Http\Requests;

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
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'bail|required|alpha|min:3|max:10',
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:3',
            'passwordAgain' => 'bail|required|same:password',
        ];
    }
}
