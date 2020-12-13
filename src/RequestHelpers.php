<?php

namespace taguz91\CommonHelpers;

use Yii;
use yii\web\HttpException;

class RequestHelpers
{

  /**
   * @return null|string
   */
  static function getUserIP()
  {
    $valids = [
      'HTTP_CLIENT_IP',
      'HTTP_X_FORWARDED_FOR',
      'HTTP_X_FORWARDED',
      'HTTP_X_CLUSTER_CLIENT_IP',
      'HTTP_FORWARDED_FOR',
      'HTTP_FORWARDED',
      'REMOTE_ADDR',
    ];

    foreach ($valids as $key) {
      if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
          $ip = trim($ip); // just to be safe

          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
          }
        }
      }
    }
  }

  /**
   * Configuracion para tareas que tardan y usan mucha memoria 
   */
  static function longRequest()
  {
    ini_set('memory_limit', '2048M');
    set_time_limit(300);
  }

  /**
   * Cargamos los datos del post
   * 
   * @throws HttpExeption - Si no tenemos valores en el post
   */
  static function getPostDataOrFail(string $message = 'No data found')
  {
    $data = self::getPostData();
    if (empty($data)) {
      throw new HttpException(405, $message);
    }
    return $data;
  }

  /**
   * Cargamos los datos del post
   */
  static function getPostData()
  {
    $data = Yii::$app->request->post();
    if (empty($data)) {
      $data = Yii::$app->request->getRawBody();
      $data = (array) json_decode($data, true);
    }

    return $data;
  }

  /**
   * Obtenemos los datos post como un objeto 
   */
  static function getPostDataAsObject() : \stdClass
  {
    $data = self::getPostData();
    $data = DataHelpers::toObject($data);
    if (is_array($data) || empty($data)) return new \stdClass;
    return $data;
  }
}
