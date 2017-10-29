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
                    'confirmPassword' => 'required|min:6|max:25',
                    'oldPassword'     => 'required|min:6|max:25'
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
                   'confirmPassword.required'   => 'تکرار پسورد الزامی است',
                   'confirmPassword.min'        => 'تعداد کارکترهای تکرار پسورد نباید کمتر از 6 رقم باشد',
                   'confirmPassword.max'        => 'تعداد کارکترهای تکرار پسورد نباید بیشتر از 25 رقم باشد',
                   'oldPassword.required'       => 'وارد کردن پسورد قبلی الزامی است',
                   'oldPassword.min'            => 'تعداد کارکترهای  پسورد قبلی نباید کمتر از 6 رقم باشد',
                   'oldPassword.max'            => 'تعداد کارکترهای  پسورد قبلی نباید بیشتر از 25 رقم باشد'
            ];
    }
}
