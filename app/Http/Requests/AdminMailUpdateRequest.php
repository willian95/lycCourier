<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMailUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            "email" => "required"
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "Correo adminsitrativo es requerido"
        ];
    }
}
