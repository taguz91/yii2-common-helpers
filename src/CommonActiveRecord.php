<?php

namespace taguz91\CommonHelpers;

trait CommonActiveRecord
{

  /**
   * @return string $id - MongoId en formato string  
   */
  public function getId(): string
  {
    return (string) $this->_id;
  }

  /**
   * @param string $campo - Atributo del documento
   * @param $default - Valor que tomara por defecto
   * 
   * @return array $rule - Regla para un valor por defecto
   */
  static function defaultRule(string $campo, $default): array
  {
    return [
      $campo, 'default',
      'value' => $default
    ];
  }

  /**
   * @param array @attributes - Atributos a transformarlos en array
   * @param array $add - Datos para agregar al nodod principal 
   *
   * @return array
   */
  public function toArrayAdd(array $attributes, array $add): array
  {
    $res = $this->toArray($attributes);
    $data = array_merge($res, $add);
    ksort($data);
    return $data;
  }
}
