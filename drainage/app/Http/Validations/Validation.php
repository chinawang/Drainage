<?php

/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 15:27
 */

namespace App\Http\Validations;

use Illuminate\Http\Request;

/**
 * Class Validation
 * @package App\Http\Validations
 * @property \Illuminate\Database\Eloquent\Model $user
 */
class Validation
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Validator constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 过滤请求参数
     *
     * @param array $keys
     * @param bool $trim
     * @return array
     */
    public function filterRequest(array $keys, $trim = true)
    {
        $input = $this->request->only($keys);

        if ( $trim ) {
            array_walk($input, function(&$value) {
                if ( is_string($value) ) {
                    $value = trim($value);
                }
            });
        }

        return array_filter($input, function($value) {
            return ! is_null($value);
        });
    }

}