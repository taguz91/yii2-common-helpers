<?php

namespace taguz91\CommonHelpers\Exceptions;

use yii\mongodb\Exception;

class DataNotFoundException extends Exception
{

  public function __construct($message, array $data = [], Exception $previous = null)
  {
    if (!empty($data)) {
      $json = json_encode($data);
      $message = $json ? $json : $message;
    }
    parent::__construct($message, 23, $previous);
  }
}
