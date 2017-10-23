<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBillValidation extends FormRequest
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
                 'factorNumber' => 'required|numeric',
                 'image'        => 'required|mimes:png,jpg',
                 'date'         => 'required'
               ];
    }
    public function messages()
    {
      return
          [
                'factorNumber.required'  => 'وارد کردن شماره فاکتور الزامی است',
                'factorNumber.numeric'   => 'شماره فاکتور باید از نوع عددی باشد',
                'image.required'         => 'لطفا فایل فاکتور انتخاب نمایید سپس درخواست ثبت فاکتور را بزنید',
                'image.mimes'            => 'پسوند فایل اتنخاب شده معتبر نیست',
                'date.required'          => 'وارد کردن فیلد تاریخ الزامی است',
          ];
    }
}
