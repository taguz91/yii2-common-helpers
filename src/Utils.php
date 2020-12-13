<?php

namespace taguz91\CommonHelpers;

use MongoDB\BSON\UTCDateTime;
use Yii;

class Utils
{

  const DATE_FORMAT = 'Y-m-d H:i:s';

  static function getNow(string $format = self::DATE_FORMAT): string
  {
    return date($format);
  }

  static function getNowTime(string $format = self::DATE_FORMAT)
  {
    return strtotime(self::getNow($format));
  }

  static function getDate(
    int $time,
    string $format = self::DATE_FORMAT
  ) {
    return date($format, $time);
  }

  static function getNowMenosDias(
    int $dias,
    string $format = self::DATE_FORMAT
  ): string {
    return date($format, strtotime("-{$dias} days"));
  }

  static function getNowMongo(): UTCDateTime
  {
    return self::getMongoDate(self::getNow());
  }

  static function getMongoDate(string $date): UTCDateTime
  {
    $date = str_replace('/', '-', $date);
    return new UTCDateTime(strtotime($date) * 1000);
  }

  /**
   * @param float|int $precio
   */
  static function getDosDecimales($precio): string
  {
    return number_format(round($precio, 2), 2, '.', '');
  }

  /**
   * Si no tenemos fecha devolvemos un string vacio
   */
  static function getDateFromMongo(
    UTCDateTime $date = null,
    string $format = self::DATE_FORMAT
  ): string {
    if (is_null($date)) return '';
    $date = (string) $date;
    return date($format, ($date / 1000));
  }

  static function getMongoDateFromTime(int $time): UTCDateTime
  {
    $date = self::getDate($time);
    return new UTCDateTime(strtotime($date) * 1000);
  }

  static function getIDActualUser()
  {
    return Yii::$app->user->identity->_id ?? null;
  }

  static function getMinutesTranscurridos(
    int $init
  ): float {
    $now = strtotime(Utils::getNow());
    $mins = ($init - $now) / 60;
    $mins = abs($mins);
    return floor($mins);
  }

  static function getUserIP()
  {
    return Yii::$app->request->userIp;
  }
}
