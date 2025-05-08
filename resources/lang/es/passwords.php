<?php

return [
    // Mensaje cuando el enlace se envía correctamente
    'sent' => 'Hemos enviado un correo electrónico con el enlace para restablecer tu contraseña.',

    // Mensaje cuando la contraseña se restablece correctamente
    'reset' => 'Tu contraseña ha sido restablecida exitosamente.',

    // Mensaje cuando el usuario no existe en la base de datos
    'user' => 'No podemos encontrar un usuario con ese correo electrónico.',

    // Mensaje cuando el token es inválido
    'token' => 'El token para restablecer la contraseña es inválido.',

    // Mensaje cuando se intenta solicitar demasiados restablecimientos
    'throttled' => 'Por favor espera un momento antes de volver a intentarlo.',

    // Tiempo de expiración del token (por defecto)
    'password' => 'Las contraseñas deben tener al menos ocho caracteres y coincidir con la confirmación.',
];
