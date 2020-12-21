<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            "lastname" => "required",
            "dni" => "required",
            "address" => "required",
            "password" => "nullable|confirmed|min:8"
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Nombre es requerido",
            "lastname.required" => "Apellido es requerido",
            "dni.required" => "DNI es requerido",
            "address.required" => "Dirección es requerida",
            "password.confirmed" => "Contraseñas no coinciden",
            "password.min" => "Contraseña debe tener al menos 8 caracteres"
        ];
    }

}
