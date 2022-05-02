<?php

namespace App\Validators\Time;
use App\Validators\BaseValidator;
use Illuminate\Support\Facades\Validator;

class TimeValidator extends BaseValidator
{

    public function validate(array $data, $updateId = null)
    {
        if (isset($data['time'])) {
            $data['time'] = $this->onlyNum($data['time']);
        }

        $validator = Validator::make(
            $data,
            [
                'time' => 'required',
                'title' => 'required|max:55',
                'description' => 'required|max:255',
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
            'time.required' => 'time is required',
            'title.required' =>  'title is required',
            'description.required' =>  'description is required',
        ];
    }
}
