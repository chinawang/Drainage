<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:04
 */

namespace Support\Exception;


class Exception extends \Exception
{
    /**
     * @var string
     */
    public $reason;

    /**
     * ValidationFailedException constructor.
     *
     * @param string $reason
     * @param string $message
     */
    public function __construct($reason = '', $message = null)
    {
        parent::__construct($message);

        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}