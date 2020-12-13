<?php

namespace taguz91\CommonHelpers\Exceptions;

use yii\mongodb\Exception;

/**
 * Exception si los valores enviados no son validos
 */
class DataInvalidException extends Exception
{

  public function __construct(array $data = [], Exception $previous = null)
  {
    parent::__construct(json_encode($data), 23, $previous);
  }
}
