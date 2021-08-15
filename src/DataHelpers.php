<?php

namespace taguz91\CommonHelpers;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use yii\helpers\ArrayHelper;
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
  ): \stdClass {
    return Json::decode(Json::encode($data), false);
  }

  /**
   * @param array[] $list
   */
  static function arrayGroupBy(array $list, string $key): array
  {
    $groups = [];
    foreach ($list as $data) {
      $keyGroup = self::getKey($data[$key]);

      $group = $groups[$keyGroup] ?? [];
      $group[] = $data;
      $groups[$keyGroup] = $group;
    }
    return $groups;
  }

  /**
   * @param \stdClass[] $list
   */
  static function objectGroupBy(array $list, string $attribute): array
  {
    $groups = [];
    foreach ($list as $data) {
      $keyGroup = self::getKey($data->{$attribute});

      $group = $groups[$keyGroup] ?? [];
      $group[] = $data;
      $groups[$keyGroup] = $group;
    }
    return $groups;
  }

  /**
   * @param array[]
   * @param string $key
   */
  static function arrayToHashMap(array $data, string $key)
  {
    $newList = [];
    foreach ($data as $val) {
      $newList[self::getKey($val[$key])] = $val;
    }
    return $newList;
  }

  /**
   * @param \stdClass
   * @param string $attribute
   */
  static function objectToHashMap(array $data, string $attribute)
  {
    $newList = [];
    foreach ($data as $val) {
      $newList[self::getKey($val->{$attribute})] = $val;
    }
    return $newList;
  }

  /**
   * Get a key for array
   */
  private static function getKey($key)
  {
    if ($key instanceof \MongoDB\BSON\ObjectId) {
      $key = (string) $key;
    }
    return $key;
  }

  static function isValidAttribute($key)
  {
    return is_string($key) && strpos($key, '-') === false;
  }

  /**
   * Create a chunck array
   */
  static function splitList(array $list, int $lengh = 2)
  {
    $count = count($list) / $lengh;
    return array_chunk($list, floor($count));
  }

  /**
   * Matriz to flat array 
   * 
   * @param array[]
   */
  static function toArrayFlat(array $matriz, bool $withKey = true)
  {
    $flat = [];
    foreach ($matriz as $data) {
      foreach ($data as $key => $value) {
        if ($withKey) {
          $flat[$key] = $value;
        } else {
          $flat[] = $value;
        }
      }
    }
    return $flat;
  }

  /**
   * Matriz to flat array
   */
  static function toArrayFlatRecursive(array $matriz, bool $withKey = true): array
  {
    $ite = new RecursiveIteratorIterator(new RecursiveArrayIterator($matriz));
    $flat = [];
    foreach ($ite as $ik => $iv) {
      if ($withKey) {
        $flat[$ik] = $iv;
      } else {
        $flat[] = $iv;
      }
    }

    return $flat;
  }

  /**
   * Associative array with shuffle order
   */
  static function shuffleAssoc(array $list): array
  {
    $keys = array_keys($list);
    shuffle($keys);
    $random = [];
    foreach ($keys as $key) {
      $random[$key] = $list[$key];
    }
    return $random;
  }

  /**
   * Sort a list from another 
   */
  static function sortByList(array $list, string $attribute, array $sort): array
  {
    $newList = [];
    $mapped = [];
    foreach ($list as $value) {
      $key = ArrayHelper::getValue($value, $attribute);
      $mapped[$key] = $value;
    }

    foreach ($sort as $key) {
      if (isset($mapped[$key])) {
        $newList[] = $mapped[$key];
      }
    }

    return $newList;
  }
}
