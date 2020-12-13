<?php

namespace taguz91\CommonHelpers;

class DataHelpers
{

  static function toArray(
    $data
  ): array {
    return json_decode(json_encode($data), true);
  }

  static function toObject(
    $data
  ): array {
    return json_decode(json_encode($data));
  }
}
