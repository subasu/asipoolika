<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendTicketValidation extends FormRequest
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

                //'units'=>'required',
                'title'=>'required|max:100',
                'description'=>'required|max:10000'
            ];
    }

    public function messages()
    {
        return
            [
              //'units.required'=>'لطفا فیلد واحد را انتخاب نمایید',
              'title.required'=>'لطفا فیلد عنوان را پر  نمایید',
              'description.required'=>'لطفا فیلد توضیحات را پر  نمایید',
              'title.max'=>'تعداد  کارکترهای وارد شده در فیلد عنوان بیش از حد مجاز میباشد',
              'description.max'=>'تعداد  کارکترهای وارد شده در فیلد توضیحات بیش از حد مجاز میباشد',
            ];
    }
}
