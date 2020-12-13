<?php

namespace taguz91\CommonHelpers;

use taguz91\CommonHelpers\Exceptions\DataNotSaveException;

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

  /**
   * 
   * @throws DataNotSaveException - Al no guardar el documento
   */
  public function saveOrFail(string $message): bool
  {
    $saved = $this->save();
    if (!$saved) {
      throw new DataNotSaveException($message, [
        'transaccion' => false,
        'errorDescripcion' => $message,
        'errors' => $this->getErrors()
      ]);
    }
    return true;
  }
}
