<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateValidation extends FormRequest
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
        return
            [
                'name' => 'required|max:100',
                'family' => 'required|max:100',
                'username' => 'required|max:100|unique:users',
                'password' => 'required|min:6|max:25|confirmed',
               // 'cellphone' => 'required|numeric',
                //'internal_phone' => 'required|numeric',
                'unit_id' => 'required',
//                'supervisor_id' => 'required',
                //'description' => 'required',
            ];
    }

    //
    public function messages()
    {
        return
            [
                'name.required' => ' فیلد نام الزامی است',
                'name.max' => ' فیلد نام حداکثر باید 100 کاراکتر باشد ',
                'family.max' => ' فیلد نام خانوادگی عبور حداکثر باید 100 کاراکتر باشد ',
                'family.required' => ' فیلد نام خانوادگی الزامی است ',
                'email.required' => ' فیلد ایمیل الزامی است',
                'email.unique' => 'این ایمیل قبلا ثبت شده است',
                'email.email' => ' فرمت ایمیل نادرست است ',
                'email.size' => ' فیلد ایمیل حداکثر باید 100 کاراکتر باشد ',
                'password.required' => ' فیلد رمز عبور الزامی است ',
                'password.confirmed' => ' فیلد رمز عبور  و تکرار آن با هم مطابقت ندارند ',
                'password.min' => ' فیلد رمز عبور حداقل باید 6 کاراکتر باشد ',
                'password.max' => ' فیلد رمز عبور حداکثر باید 25 کاراکتر باشد ',
                //'cellphone.required' => ' فیلد موبایل الزامی است ',
                //'cellphone.numeric' => ' فیلد موبایل عددی است',
                //'internal_phone.required' => ' فیلد شماره داخلی الزامی است ',
                //'internal_phone.numeric' => ' فیلد شماره داخلی عددی است',
                //'internal_phone.size' => ' فیلد شماره داخلی باید 11 رقمی باشد',
                'unit_id.required' => 'لطفا فیلد واحد را اتنخاب نمایید',
                //'supervisor_id.required' => ' فیلد سرپرست الزامی است',
                //'description.required' => ' فیلد توضیحات الزامی است',
            ];
    }
}
