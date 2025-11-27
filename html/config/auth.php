<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Define cada 'guard' (mecanismo de autenticación) de la aplicación. 
    | Hemos añadido 'admin' y 'corporate' para los diferentes roles, además de 'web'.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [ // Guard por defecto, lo usaremos para el usuario Viajero (Particular)
            'driver' => 'session',
            'provider' => 'viajeros',
        ],
        'admin' => [ // Guard para el usuario Administrador
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'corporate' => [ // Guard para el usuario Corporativo (Hotel)
            'driver' => 'session',
            'provider' => 'hoteles',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Define cómo se recuperan los usuarios de la base de datos para cada guard.
    | Hemos añadido tres providers para las tres tablas de usuario separadas.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'hoteles' => [
            'driver' => 'eloquent',
            'model' => App\Models\Hotel::class,
        ],
        'viajeros' => [
            'driver' => 'eloquent',
            'model' => App\Models\Viajero::class,
        ],

        // Provider por defecto para compatibilidad
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Puedes especificar múltiples configuraciones de restablecimiento de contraseña
    | si tienes más de una tabla de usuarios.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        // Opcionalmente puedes definir configuraciones de reseteo para los otros tipos
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Define la cantidad de segundos antes de que caduque una confirmación de contraseña.
    |
    */

    'password_timeout' => 10800,

];