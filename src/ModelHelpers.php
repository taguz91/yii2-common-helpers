<?php 

namespace taguz91\CommonHelpers;

use Yii;
use yii\base\Model;

trait ModelHelpers {

  /**
   * @return array $attributes - Nombre de los atributos de la clase
   */
  public function getKeysAttributes() {
    return array_keys($this->attributes);
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
  public function getCommonValidators(array $notRequired = []) {
    return [
      // Todos seguros
      [
         $this->getKeysAttributes(),
        'safe',
      ],
      // Agregamos que todos son requreidos 
      [
        array_diff($this->getKeysAttributes(), $notRequired),
        'required',
      ],
    ];
  }

  /**
   * Agregamos validaciones por defecto en strings. 
   * 1. Todos deben ser string
   * 2. Todos deben pasar por el filtro de trim
   * 
   * @return array $validators
   */
  public function getCommonStringValidators(array $attributes = []) {
    $attributes = empty($attributes) ? $this->getKeysAttributes() : $attributes;

    return [
      // Todos deben ser string 
      [
        $attributes,
        'string',
      ],
      // Agregamos trim a todos los atributos 
      [
        $attributes,
        'filter', 
        'filter' => 'trim'
      ]
    ];
  }

  static function defaultRule(string $campo, $default) : array {
    return [
      $campo, 'default',
      'value' => $default
    ];
  } 

  function loadedFromPost(string $nodo = null) : bool {
    return $this->load(Yii::$app->request->post(), $nodo);
  }

  function loadedFromGet(string $nodo = null) : bool {
    return $this->load(Yii::$app->request->get(), $nodo);
  }

  /**
   * Cargamos los datos por post y devolvemos el objeto
   * 
   * @return $this
   */
  function loadFromPost(string $nodo = null) : Model {
    $this->load(Yii::$app->request->post(), $nodo);
    return $this;
  }

}