<?php

namespace taguz91\CommonHelpers;

use taguz91\CommonHelpers\Exceptions\DataNotFoundException;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;

trait ActiveRecordQuerys
{

  /**
   * Buscamos un objeto por su id y empresa configurada
   *
   * @param string|\MongoDB\BSON\ObjectId
   *
   * @return \yii\mongodb\ActiveRecord
   */
  static function findByIdOrFail($id, $message = 'No encontramos el registro'): ActiveRecord
  {
    if (($model = self::getOneEmpresa($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException($message);
    }
  }

  /**
   * @param array $condition - Condicion de la consulta 
   * 
   * @throws DataNotFoundException - Al obtener un null en una consulta
   */
  static public function findOneOrFail(array $condition, string $message = ''): ActiveRecord
  {
    $res = self::findOne($condition);
    self::validResult($res, [
      'condition' => $condition
    ], $message);

    return $res;
  }

  /**
   * @param array $condition - Condicion 
   * @param string $mensaje - Error si no encontramos lo que buscamos 
   */
  static function findAllOrFail(array $condition, string $mensaje = ''): array
  {
    $data = self::findAll($condition);
    self::validResult($data, [
      'condition' => $condition
    ], $mensaje);

    return $data;
  }

  /**
   * @param array $select - Lista de valores a buscar
   * @param array $condition - Condicion de la consulta
   * @param string $errorMessage - Error para la excepcion 
   * 
   * @throws DataNotFoundException - Al obtener un null en una consulta
   */
  static function selectOneOrFail(
    array $select,
    array $condition,
    string $message = ''
  ): ?ActiveRecord {
    $res = self::selectOne($select, $condition);
    self::validResult($res, [
      'select' => $select,
      'condition' => $condition,
    ], $message);
    return $res;
  }

  /**
   * @param array $select - Attributos a buscar
   * @param array @condition - Condifcion
   *
   * @return \yii\mongodb\ActiveRecord
   */
  static public function selectOne(array $select, array $condition): ActiveRecord
  {
    return self::find()
      ->select($select)
      ->where($condition)
      ->one();
  }

  /**
   * @param array $select - Attributos a buscar 
   * @param array $condition - Condicion 
   *
   * @return \yii\mongodb\ActiveRecord[]
   *
   * @psalm-return array<array-key, \yii\mongodb\ActiveRecord>
   */
  static function selectAll(array $select, array $condition, $orderBy = ''): array
  {
    return self::find()
      ->select($select)
      ->where($condition)
      ->orderBy($orderBy)
      ->all();
  }

  /**
   * En este agregamos por defecto el parametro de empCodigo
   *
   * @return  \yii\mongodb\ActiveRecord
   */
  static function getOneEmpresa($id): ?ActiveRecord
  {
    return self::findOne([
      '_id' => $id,
      'empCodigo' => Yii::$app->params['empCodigo']
    ]);
  }

  /**
   * Validamos que el resultado no sea null
   * 
   * @throws DataNotFoundException - Al obtener un null en una consulta
   */
  static function validResult($res, array $meta, string $message = '')
  {
    // Si es un array comprobamos que no este vacio si no devolvemos null para que no sea valido 
    $res = is_array($res) ? (empty($res) ? null : $res) : $res;

    if (is_null($res)) {
      $message = $message === '' ? 'No encontramos lo que buscabas' : $message;

      throw new DataNotFoundException($message, [
        'transaccion' => false,
        'errorDescripcion' => $message,
        'meta' => $meta
      ]);
    }
  }
}
