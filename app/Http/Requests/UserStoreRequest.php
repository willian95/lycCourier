<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed",
            "roleId" => "required|exists:roles,id" 
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Nombre es requerido",
            "email.required" => "Email es requerido",
            "email.email" => "Email no es válido",
            "email.unique" => "Este email ya existe",
            "password.required" => "Clave es requerida",
            "password.confirmed" => "Claves no coinciden",
            "roleId.required" => "Rol es requerido",
            "roleId.exists" => "Rol elegido no es válido" 
        ];
    }
}
