<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingProcessRequest extends FormRequest
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
            "packageId" => "required",
            "description" => "required",

        ];
    }

    public function messages()
    {
        return [
            "packageId.required" => "Debe seleccionar un tipo de paquete",
            "description.required" => "Debe agregar una descripción",
            
        ];
    }
}
