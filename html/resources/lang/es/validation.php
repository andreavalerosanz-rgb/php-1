<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mensajes de validación básicos
    |--------------------------------------------------------------------------
    */

    'required' => 'El campo :attribute es obligatorio.',
    'string'   => 'El campo :attribute no es válido.',
    'email'    => 'El correo electrónico no tiene un formato válido.',
    'confirmed'=> 'Las contraseñas no coinciden.',
    'in'       => 'El valor seleccionado para :attribute no es válido.',

    'min' => [
        'string' => 'La :attribute debe tener al menos :min caracteres.',
    ],

    'max' => [
        'string' => 'La :attribute no puede superar los :max caracteres.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nombres legibles de los campos
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'role' => 'tipo de cuenta',
        'nombre' => 'nombre',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'apellido1' => 'primer apellido',
        'apellido2' => 'segundo apellido',
        'direccion' => 'dirección',
        'codigoPostal' => 'código postal',
        'ciudad' => 'ciudad',
        'pais' => 'país',
    ],
];
