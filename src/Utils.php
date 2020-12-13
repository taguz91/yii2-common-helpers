<?php

namespace taguz91\CommonHelpers;

use MongoDB\BSON\UTCDateTime;
use Yii;

class Utils
{

  static function getNow(string $format = 'Y-m-d H:i:s'): string
  {
    return date($format);
  }

  static function getNowMenosDias(
    int $dias, 
    string $format = 'Y-m-d H:i:s'
  ): string {
    return date($format, strtotime("-{$dias} days"));
  }

  static function getNowMongo(): UTCDateTime
  {
    return self::getMongoDate(self::getNow());
  }

  static function getMongoDate(string $date) : UTCDateTime {
    return new UTCDateTime(strtotime($date) * 1000);
  }

  /**
   * @param float|int $precio
   */
  static function getDosDecimales($precio) : string {
    return number_format(round($precio, 2), 2, '.', '');
  }

  /**
   * Si no tenemos fecha devolvemos un string vacio
   */
  static function getDateFromMongo(
    UTCDateTime $date = null,
    string $format = 'Y-m-d H:i:s' 
  ) : string {
    if(is_null($date)) return '';
    $date = (string) $date;
    return date($format, ($date / 1000));
  }

  static function getIDActualUser() {
    return Yii::$app->user->identity->_id ?? null;
  }

  static function getMinutesTranscurridos(
    int $init
  ) : float {
    $now = strtotime(Utils::getNow());
    $mins = ($init - $now) / 60;
    $mins = abs($mins);
    return floor($mins);
  }

  static function getUserIP() {
    return Yii::$app->request->userIp;
  }

}
