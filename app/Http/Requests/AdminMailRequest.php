<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            "email" => "required|unique:admin_mails,email"
        ];
    }


    public function messages()
    {
        return [
            "email.required" => "Correo es requerido",
            "email.unique" => "Este correo ya existe"
        ];
    }
}
