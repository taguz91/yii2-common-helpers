<?php

namespace taguz91\CommonHelpers\Exceptions;

use yii\helpers\Json;
use yii\mongodb\Exception;

/**
 * Exception si los valores enviados no son validos
 */
class DataInvalidException extends Exception
{

  public function __construct(array $data = [], Exception $previous = null)
  {
    parent::__construct(Json::encode($data), 23, $previous);
  }
}
