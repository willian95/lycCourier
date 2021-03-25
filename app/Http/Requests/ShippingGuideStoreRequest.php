<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingGuideStoreRequest extends FormRequest
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
            "guide" => "unique:shipping_guides,guide|required",
            "shippings" => "required|array"
        ];
    }

    public function messages(){

        return [
            "guide.unique" => "Esta guía ya existe",
            "guide.required" => "Número de guía es requerida",
            "shippings.array" => "Envíos no validos",
            "shippings.required" => "Debe seleccionar envíos"
        ];

    }
}
