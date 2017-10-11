<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptServiceRequestValidation extends FormRequest
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
            //
                'rate'=>'required',
                'price'=>'required'
            ];
    }

    public function messages()
    {
        return
            [
                //
                'rate.required'=>'پر کردن فیلد نرخ الزامی است',
                'price.required'=>'پر کردن فیلد قیمت الزامی است',
            ];
    }
}
