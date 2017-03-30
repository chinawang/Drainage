<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:16
 */

namespace App\Http\Logic\Maintenance;


use App\Repositories\Maintenance\FailureRepository;
use Support\Logic\Logic;

class FailureLogic extends Logic
{
    /**
     * @var FailureRepository
     */
    protected $failureRepository;

    /**
     * FailureLogic constructor.
     * @param FailureRepository $failureRepository
     */
    public function __construct(FailureRepository $failureRepository)
    {
        $this->failureRepository = $failureRepository;
    }
}