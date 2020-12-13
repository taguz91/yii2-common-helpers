<?php

namespace taguz91\CommonHelpers;

use Yii;
use yii\web\Response;

class ResponseHelpers
{

  static function toHTMLResponse()
  {
    Yii::$app->response->format = Response::FORMAT_HTML;
    Yii::$app->response->headers->remove('Content-Type', 'application/json');
  }

  static function toAPIResponse()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    Yii::$app->response->headers->add('Content-Type', 'application/json');
  }

  static function isJSON()
  {
    return Yii::$app->response->format === Response::FORMAT_JSON || Yii::$app->request->isAjax;
  }

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

  static function dataResponse($data): array
  {
    return [
      'transaccion' => true,
      'data' => $data
    ];
  }

  /**
   * Respuesta correcta con un mensaje
   */
  static function messageResponse(string $message): array
  {
    return [
      'transaccion' => true,
      'mensaje' => $message
    ];
  }

  static function mergeResponse(array $data): array
  {
    $default = [
      'transaccion' => true,
    ];
    return array_merge($default, $data);
  }
}
