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
                   'code[]'         => 'numeric',
                   //'description[]'  => 'required',
                   'moeinOffice[]'  => 'numeric',
                   'generalPrice[]' => 'numeric',
                   'deduction[]'    => 'numeric',
                   'payedPrice[]'   => 'numeric',
                   'page[]'         => 'numeric',
                   'row[]'          => 'numeric'
               ];


    }

    public function messages()
    {
        return
            [
                 'code[].numeric'             => 'لطفا فیلد کد را از نوع عدد  وارد نمایید',
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
