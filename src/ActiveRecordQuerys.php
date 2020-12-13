<?php

namespace taguz91\CommonHelpers;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;

trait ActiveRecordQuerys
{

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
   * Buscamos un objeto por su id y empresa configurada
   *
   * @param string|MongoId
   *
   * @return \yii\mongodb\ActiveRecord
   */
  static function findByIdOrFail($id): ActiveRecord
  {
    if (($model = self::getOneEmpresa($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException('No encontramos el registro');
    }
  }
}
