<?php

namespace taguz91\CommonHelpers;

class MapperHelpers
{

    /**
     * Create array key value removing a string in the key
     * @param string   $prefix
     * @param string[] $list
     */
    static function hashRemovePrefix(string $prefix, array $list): array
    {
        $keys = array_map(function (string $value) use ($prefix) {
            $key = str_replace($prefix, '', $value);
            return lcfirst($key);
        }, $list);

        return array_combine($keys, $list);
    }

    /**
     * Matriz to array key value, cocat the old keys
     * ```
     * // Datos entrada
     * $data = [
     *  'val'   => 1,
     *  'sub'   => [
     *      'internal' => 'dos',
     *  ]
     * ];
     * // Datos salida
     * $mapped = [
     *  'val'   => 1,
     *  'sub.internal' => 'dos'
     * ];
     * ```
     */
    static function hashMatriz(array $data, string $root = ''): array
    {
        $newData = [];
        foreach ($data as $name => $value) {
            $key = !empty($root) ? "{$root}.{$name}" : $name;

            if (is_array($value)) {
                $newData[$key] = self::hashMatriz($value, $key);
            } else {
                $newData[$key] = $value;
            }
        }
        return DataHelpers::toArrayFlatRecursive($newData);
    }
}
