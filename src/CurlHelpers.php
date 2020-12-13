<?php

namespace taguz91\CommonHelpers;

use Exception;

class CurlHelpers
{

  /**
   * Realizamos una peticion GET, POST o DELETE utilizando curl
   * 
   * @param string $url - Url a la cual realizaremos el request
   * @param array $data - Data que enviaremos a la peticion
   * @param array $headers - Headers a usar 
   * @param string $method - VERB de la peticion (POST | GET | DELETE)
   * 
   * @return array|object - Error obtenido, objeto base
   */
  static function curl(
    string $url,
    array $data,
    array $headers = [],
    string $method = 'POST' | 'GET' | 'DELETE',
    bool $toArray = false,
    bool $bodyParams = false
  ) {
    $conexion = curl_init();

    $dataString = http_build_query($data);

    if (count($headers) > 0) {
      curl_setopt($conexion, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($conexion, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($conexion, CURLOPT_HEADER, 0);

    switch ($method) {
      case 'POST':
        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_POST, count($data));

        break;
      case 'GET':
        if ($dataString != "") {
          $url = $url . '?' . $dataString;
        }
        curl_setopt($conexion, CURLOPT_URL, $url);
        break;
      case 'DELETE':
        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_POST, count($data));
        break;
      default:
        # code...
        break;
    }

    curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($conexion, CURLOPT_CUSTOMREQUEST, $method);
    if ($method === 'POST') {
      if ($bodyParams) {
        curl_setopt($conexion, CURLOPT_POSTFIELDS, json_encode($data));
      } else {
        curl_setopt($conexion, CURLOPT_POSTFIELDS, $dataString);
      }
    }

    $rawResponse = curl_exec($conexion);
    curl_close($conexion);

    $resultado = is_string($rawResponse) ? json_decode($rawResponse, $toArray) : $rawResponse;

    if (is_object($resultado) || is_array($resultado)) {
      return $resultado;
    }

    throw new Exception("No pudimos realiar la peticion {$method} a: {$url} >>> " . (is_string($rawResponse) ? $rawResponse : ''));
  }

  /**
   * @param string $url - Url para el request
   * @param array $data - Data que enviaremos
   *
   * @return array|object
   */
  static function post(string $url, array $data, bool $toArray = false, bool $bodyParams = false)
  {
    return self::curl($url, $data, [
      'Content-type: application/x-www-form-urlencoded'
    ], 'POST', $toArray, $bodyParams);
  }

  /**
   * @return array|object
   */
  static function get(string $url, bool $toArray = false)
  {
    return self::curl($url, [], [], 'GET', $toArray);
  }

  /**
   * Realizamos una peticion post utilizan la autentificacion - Authorization
   *
   * @param string $url - Url para el request
   * @param array $data - Data que enviaremos
   * @param string $token - Token de tipo Authorization
   *
   * @return array|object
   */
  static function executePOSTAuth(string $url, array $data, string $token)
  {
    return self::curl($url, $data, [
      "Authorization: " . $token,
    ], 'POST');
  }

  /**
   * @param string $url - Url para el request
   * @param array $data - Data que enviaremos
   * @param string $token - Token de tipo Authorization
   *
   * @return array|object
   */
  static function executeGETAuth(string $url, array $data, string $token)
  {
    return self::curl($url, $data, [
      "Authorization: " . $token,
    ], 'GET');
  }
}
