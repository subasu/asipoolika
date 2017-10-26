<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CostDocumentValidation extends FormRequest
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
                   'code[]'         => 'integer|min:0',
                   //'description[]'  => 'required',
                   'moeinOffice[]'  => 'integer|min:0',
                   'generalPrice[]' => 'numeric|min:0',
                   'deduction[]'    => 'numeric|min:0',
                   'payedPrice[]'   => 'numeric|min:0',
                   'page[]'         => 'numeric|min:0',
                   'row[]'          => 'numeric|min:0'
               ];


    }

    public function messages()
    {
        return
            [
                 'code[].integer'             => 'لطفا فیلد کد را از نوع عدد  وارد نمایید',
                 'code[].min:0'             => 'لطفا از وارد کردن اعداد منفی خودداری نمایید',
                 //'description[].required'     => 'پر کردن فیلد شرح الزامی است',
                 'moeinOffice[].numeric'      => 'لطفا فیلد دفتر معین را از نوع عدد صحیح وارد نمایید',
                 'deduction[].numeric'        => 'لطفا فیلد کسور را از نوع عدد صحیح وارد نمایید',
                 'generalPrice.numeric'     => 'لطفا فیلد قیمت اصلی را از نوع عدد صحیح وارد نمایید',
                 'payedPrice.numeric'       => 'لطفا فیلد هزینه پرداختی  را از نوع عدد صحیح وارد نمایید',
                 'page.numeric'             => 'لطفا فیلد صفحه را از نوع عدد صحیح وارد نمایید',
                 'row.numeric'              => 'لطفا فیلد ردیف را از نوع عدد صحیح وارد نمایید',
            ];
    }
}
