<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 16:28
 */

namespace App\Exceptions;

class UpdateFailedException extends ErrorException
{
    /**
     * UpdateFailedException constructor.
     * @param string $message
     */
    public function __construct($message = null)
    {
        $message = is_null($message) ? trans('validation.common.update_failed') : $message;
        parent::__construct(422, $message);
    }
}