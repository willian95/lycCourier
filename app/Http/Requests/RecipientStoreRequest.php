<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipientStoreRequest extends FormRequest
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
            "name" => "required",
            "email" => "nullable|email",
            "address" => "nullable",
            "phone" => "nullable"
        ];
    }

    public function messages(){

        return [
            "name.required" => "Nombre es requerido",
            "email.required" => "Email es requerido",
            "email.email" => "Email no es válido",
            "email.unique" => "Este email ya está registrado",
            "address.required" => "Dirección es requerida",
            "phone.required" => "Teléfono es requerido"
        ];

    }
}
