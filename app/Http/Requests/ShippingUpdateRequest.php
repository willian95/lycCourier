<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingUpdateRequest extends FormRequest
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
            "status" => "required|exists:shipping_statuses,id"
        ];
    }

    public function messages()
    {
        return [
            "status.required" => "Status es requerido",
            "status.exists" => "Status seleccionado no es válido"
        ];
    }
}
