<?php

namespace App\Validators\User;

use App\Validators\BaseValidator;
use Illuminate\Support\Facades\Validator;

class UserValidator extends BaseValidator
{

    public function validate(array $data, $updateId = null)
    {
        $validator = Validator::make(
            $data,
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            $this->messages()
        );

        return $this->response($validator);
    }

    public function registationValidate(array $data, $updateId = null)
    {
        $validator = Validator::make(
            $data,
            [
                'name' => 'required|max:55',
                'email' => 'required|email',
                'password' => 'required',
            ],
            $this->messages()
        );

        return $this->response($validator);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'e_email_required',
            'email.email' => 'e_email_invalid',
            'password.required'  => 'e_password_required',
            'name.required'  => 'e_password_required',
        ];
    }
}
