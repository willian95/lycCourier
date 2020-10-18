<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingStoreRequest extends FormRequest
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
            "tracking" => "required|unique:shippings,tracking",
            "recipientId" => "required|exists:recipients,id",
            "packageId" => "required|exists:boxes,id",
            "description" => "required",
            "pieces" => "required|integer|min:1",
            "length" => "required|numeric|min:0",
            "height" => "required|numeric|min:0",
            "width" => "required|numeric|min:0",
            "weight" => "required|numeric|min:0"
        ];
    }

    public function messages(){

        return [ 

            "tracking.required" => "Tracking es requerido",
            "tracking.unique" => "Este tracking ya existe",
            "recipientId.required" => "Debe seleccionar un destinatario",
            "recipientId.exists" => "Destinatario seleccionado no es válido",
            "packageId.required" => "Debe seleccionar un tipo de paquete",
            "packageId.exists" => "Tipo de paquete seleccionado no es válido",
            "description.required" => "Descripción es requerida",
            "pieces.required" => "Número de piezas es requerido",
            "pieces.integer" => "Número de piezas debe ser un número entero",
            "pieces.min" => "Número de piezas debe ser mayor a 0",
            "length.required" => "Largo es requerido",
            "length.numeric" => "Largo debe ser un número",
            "length.min" => "Largo debe ser mayor a 0",
            "height.required" => "Alto es requerido",
            "height.numeric" => "Alto debe ser un número",
            "height.min" => "Alto debe ser mayor a 0",
            "width.required" => "Ancho es requerido",
            "width.numeric" => "Ancho debe ser un número",
            "width.min" => "Ancho debe ser mayor a 0",
            "weight.required" => "Peso es requerido",
            "weight.numeric" => "Peso debe ser un número",
            "weight.min" => "Peso debe ser mayor a 0",

        ];


    }

}
