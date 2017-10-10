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
                   'code'         => 'int',
                   //'description'  => 'required',
                   'moeinOffice[]'  => 'int',
                   'generalPrice' => 'int',
                   'deduction'    => 'int',
                   'payedPrice'   => 'int',
                   'page'         => 'int',
                   'row'          => 'int'
               ];

        foreach($this->request->get('items') as $key => $val)
        {
            $rules['items.'.$key] = 'int';
        }

        return $rules;
    }

    public function messages()
    {
        return
            [
                 'code.number'             => 'لطفا فیلد کد را از نوع عدد صحیح وارد نمایید',
                 //'description.required' => 'پر کردن فیلد شرح الزامی است',
                 'mainOffice[].int'       => 'لطفا فیلد دفتر معین را از نوع عدد صحیح وارد نمایید',
                 'deduction.int'        => 'لطفا فیلد کسور را از نوع عدد صحیح وارد نمایید',
                 'generalPrice.int'     => 'لطفا فیلد قیمت اصلی را از نوع عدد صحیح وارد نمایید',
                 'payedPrice.int'       => 'لطفا فیلد هزینه پرداختی  را از نوع عدد صحیح وارد نمایید',
                 'page.int'             => 'لطفا فیلد صفحه را از نوع عدد صحیح وارد نمایید',
                 'row.int'              => 'لطفا فیلد ردیف را از نوع عدد صحیح وارد نمایید',
            ];
    }
}
