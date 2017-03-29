<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 16:25
 */

namespace App\Exceptions;


class CreateFailedException extends ErrorException
{
    /**
     * CreateFailedException constructor.
     * @param string $message
     */
    public function __construct($message = null)
    {
        $message = is_null($message) ? trans('validation.common.create_failed') : $message;
        parent::__construct(422, $message);
    }
}