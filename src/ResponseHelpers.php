<?php

namespace taguz91\CommonHelpers;

class ResponseHelpers
{

  static function mergeMessageResponse(string $message, array $data): array
  {
    $default = [
      'transaccion' => true,
      'mensaje' => $message
    ];
    return array_merge($default, $data);
  }

  static function mergeBasicError(string $message, array $data): array
  {
    $default = [
      'transaccion' => false,
      'errorDescripcion' => $message,
    ];
    return array_merge($default, $data);
  }
}
