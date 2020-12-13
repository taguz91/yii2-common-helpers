<?php

namespace taguz91\CommonHelpers;

use yii\helpers\Json;

class DataHelpers
{

  static function toArray(
    $data
  ): array {
    return Json::decode(Json::encode($data));
  }

  static function toObject(
    $data
  ): array {
    return Json::decode(Json::encode($data), false);
  }
}
