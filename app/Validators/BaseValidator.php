<?php

namespace App\Validators;
use Illuminate\Validation\Validator;

abstract class BaseValidator implements ValidatorInterface
{
    /* validation rules */
    //const PASSWORD_RULE = 'required|min:12|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/|confirmed';
    const PASSWORD_RULE = 'required|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{6,}$/';


    public abstract function validate(array $data, $updateId = null);

    public abstract function messages();

    protected function response(Validator $validator)
    {
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->getMessageBag()->messages() as $key => $message) {
                foreach ($message as $m) {
                    $errors[] = [
                        "field"   => $key,
                        "message" => $m,
                    ];
                }
            }

            return (object)["valid" => false, "errors" => json_encode($errors)];
        }

        return (object)["valid" => true, "errors" => ""];
    }

    /**
     * Used to sanitise input and get only the alphabetic chars from a string (for checking people's name, for example)
     * @param $input
     * @return mixed
     */
    public function onlyAlpha($input)
    {
        return preg_replace('/[^a-zA-Z]/', '', $input);
    }

    /**
     * Used to sanitise input and get only the alphabetic chars from a string (for checking addresses, for example)
     * @param $input
     * @return mixed
     */
    public function onlyAlphaNum($input)
    {
        return preg_replace('/[^a-zA-Z0-9]\ \.\'/', '', $input);
    }

    public function onlyNum($input)
    {
        return preg_replace('/[^0-9]/', '', $input);
    }
}

