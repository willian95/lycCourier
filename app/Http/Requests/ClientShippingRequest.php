<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientShippingRequest extends FormRequest
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
            "tracking" => "required|unique:shippings",
            "address" => "required",
            "products" => "required|min:1"
        ];
    }

    public function messages()
    {
        return [
            "tracking.required" => "Tracking es requerido",
            "tracking.unique" => "Este tracking ya existe",
            "address.required" => "Dirección es requerida",
            "products.required" => "Debe agregar productos a su envío",
            "products.min" => "Debe agregar productos a su envío"
        ];
    }
}
