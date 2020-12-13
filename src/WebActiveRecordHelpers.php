<?php

namespace taguz91\CommonHelpers;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

trait WebActiveRecordHelpers
{
  
  use ActiveRecordQuerys, CommonActiveRecord;

  /**
   * Agregamos de forma automatica las fecha de: 
   * 1. Creacion
   * 2. Actualizacion
   */
  public function behaviors()
  {
    return [
      [
        'class' => TimestampBehavior::class,
        'createdAtAttribute' => 'createdAt',
        'updatedAtAttribute' => 'updatedAt',
        'value' => Utils::getNowMongo(),
      ],
      [
        'class' => BlameableBehavior::class,
        'createdByAttribute' => 'createdBy',
        'updatedByAttribute' => 'updatedBy',
        'value' => Utils::getIDActualUser(),
      ],
      'typecast' => [
        'class' => AttributeTypecastBehavior::class,
        // 'attributeTypes' will be composed automatically according to `rules()`
      ],
    ];
  }

  /**
   * Agregams validaciones por defecto: 
   * 1. safe - Previene inyeccion de codigo
   * 2. required - Todos los campos requeridos
   * 
   * @param array $notRequired - Los agregamos como no requeridos
   * 
   * @return array $validators
   */
  public function getCommonValidators(array $notRequired = [])
  {
    $notRequired = array_merge($notRequired, [
      '_id',
      'createdAt',
      'updatedAt',
      'createdBy',
      'updatedBy'
    ]);
    return [
      // Todos seguros
      [
        $this->attributes(),
        'safe',
      ],
      // Agregamos que todos son requreidos 
      [
        array_diff($this->attributes(), $notRequired),
        'required',
      ],
    ];
  }

  /**
   * Agrega los atributos comunes 
   * 1. _id
   * 2. empCodigo
   * 3. createdAt
   * 4. updatedAt
   * 
   * @param array $attributes - Atributos que tiene el documento
   * 
   * @return array $attributes - Combina los atributos base con los comunes
   */
  public function addCommonAttributes(array $attributes): array
  {
    $common = [
      'createdAt',
      'updatedAt',
      'createdBy',
      'updatedBy',
    ];

    return array_merge([
      '_id',
      'empCodigo'
    ], $attributes, $common);
  }

  /**
   * 
   * @param string|\MongoDB\BSON\ObjectId $id - Id del objeto a eliminar 
   */
  static function softDeleteById($id, string $estado): int
  {
    $deleted = self::updateAll(
      [$estado => Yii::$app->params['ESTADO_ANULADO']],
      ['_id' => $id]
    );
    self::deleteFlash($deleted > 0);
    return $deleted;
  }

  /**
   * @param string|\MongoDB\BSON\ObjectId $id - Id del objeto a eliminar 
   *
   * @return void
   */
  static function deleteById($id)
  {
    $model = self::findByIdOrFail($id);
    $deleted = $model->delete();
    self::deleteFlash($deleted);
  }

  /**
   * @return void
   */
  private static function deleteFlash(bool $deleted)
  {
    if ($deleted) {
      Yii::$app->session->setFlash('success', 'Registro eliminado');
    } else {
      Yii::$app->session->setFlash('error', 'No pudimos eliminar el registro');
    }
  }

}
