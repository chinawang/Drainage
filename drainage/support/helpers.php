<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 11:14
 */

if ( !function_exists('hex_to_bool') ) {

    /**
     * 将二进制字符串转换bool数组
     * 比如将'101'转换为[true, false, true]
     *
     * @param string $hex
     * @param int $length 数组长度, 如果$hex长度小于$length将在前面补0
     * @return array
     */
    function hex_to_bool($hex, $length)
    {
        $hex = str_pad($hex, $length, "0", STR_PAD_RIGHT);
        $hexes = str_split($hex);

        return array_map(function($value) {
            return (bool) $value;
        }, $hexes);
    }

}

if ( !function_exists('array_sort_by') ) {

    /**
     * 二维数组排序, 不保留键名
     *
     * @param array $array
     * @param string $key
     * @param string $type
     * @return bool
     */
    function array_sort_by(array &$array, $key, $type = 'asc')
    {
        $type = strtolower($type);
        $type == 'desc' || $type = 'asc';
        return usort($array, function($prev, $next) use($key, $type) {
            $direction = $prev[$key] < $next[$key];
            if ( $direction ) {
                return $type == 'asc' ? -1 : 1;
            } else {
                return $type == 'asc' ? 1 : -1;
            }
        });
    }

}

if ( !function_exists('array_ksort_by') ) {

    /**
     * 二维数组排序, 保留键名
     *
     * @param array $array
     * @param string $key
     * @param string $type
     * @return bool
     */
    function array_ksort_by(array &$array, $key, $type = 'asc')
    {
        $type = strtolower($type);
        $type == 'desc' || $type = 'asc';
        return uksort($array, function($prev, $next) use($key, $type) {
            $direction = $prev[$key] < $next[$key];
            if ( $direction ) {
                return $type == 'asc' ? -1 : 1;
            } else {
                return $type == 'asc' ? 1 : -1;
            }
        });
    }

}

if ( !function_exists('array_index_by') ) {

    /**
     * 二维数组建立以其key为索引的数组
     * 一般需确保key的值具有唯一性, 否则会出现覆盖的情况
     *
     * @param $array
     * @param string $key
     * @return mixed
     */
    function array_index_by($array, $key)
    {
        $indexedArray = [];
        foreach ($array as $value) {
            $index = $value[$key];
            $indexedArray[$index] = $value;
        }
        return $indexedArray;
    }

}

if ( !function_exists('trans') ) {

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array|null
     */
    function trans($key, array $replace = [], $locale = null, $fallback = true)
    {
        return app('translator')->get($key, $replace, $locale, $fallback);
    }

}

if ( !function_exists('parseEnum') ) {

    /**
     * 解析枚举类型数据
     *
     * @param $section
     * @param $key
     * @param $default
     * @param bool $reverse
     * @return int|string
     */
    function parseEnum($section, $key, $default = null, $reverse = false)
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = app('config');

        $mapping = $config->get('enum.' . $section);

        if ( empty($mapping) ) {
            return $default;
        }

        if ( $reverse ) {
            $mapping = array_flip($mapping);
        }

        return array_get($mapping, $key, $default);
    }

}

if ( !function_exists('array_unique_by') ) {

    /**
     * 使二维数组以某键值唯一
     *
     * @param $array
     * @param $key
     * @return array
     */
    function array_unique_by($array, $key)
    {
        if ( $array instanceof \Illuminate\Contracts\Support\Arrayable ) {
            $array = $array->toArray();
        }

        $uniqueValues = [];

        $array = array_filter($array, function ($item) use ($key, &$uniqueValues) {
            $value = $item[$key];

            if ( in_array($value, $uniqueValues) ) {
                return false;
            } else {
                array_push($uniqueValues, $value);
                return true;
            }
        });

        return $array;
    }

}