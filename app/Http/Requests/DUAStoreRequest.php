<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DUAStoreRequest extends FormRequest
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
            "hawb" => "required",
            "esser" => "required",
            "client" => "required",
            "volante" => "required",
            "tc" => "required",
            "arrivalDate" => "required|date",
            "dua" => "required",
            "manifest" => "required",
            "awb" => "required",
            "pieces" => "required|integer",
            "weight" => "required|numeric"
        ];
    }
}
