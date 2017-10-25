<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPasswordValidation extends FormRequest
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
            //
                    'password'        => 'required|min:6|max:25',
                    'confirmPassword' => 'required|min:6|max:25'
                ];
    }


    //
    public function messages()
    {
        return
            [
                   'password.required'          => 'وارد کردن پسورد الزامی است',
                   'password.min'               => 'تعداد کارکترهای پسورد نباید کمتر از 6 رقم باشد',
                   'password.max'               => 'تعداد کارکترهای پسورد نباید بیشتر از 25 رقم باشد',
                   'confirmPassword.required'   => 'تکرا پسورد الزامی است',
                   'confirmPassword.min'        => 'تعداد کارکترهای تکرار پسورد نباید کمتر از 6 رقم باشد',
                   'confirmPassword.max'        => 'تعداد کارکترهای تکرار پسورد نباید بیشتر از 25 رقم باشد'
            ];
    }
}
