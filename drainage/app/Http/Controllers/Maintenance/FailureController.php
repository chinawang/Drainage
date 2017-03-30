<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Validations\Maintenance\FailureValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FailureController extends Controller
{
    /**
     * @var FailureLogic
     */
    protected $failureLogic;

    /**
     * @var FailureValidation
     */
    protected $failureValidation;

    /**
     * FailureController constructor.
     * @param FailureLogic $failureLogic
     * @param FailureValidation $failureValidation
     */
    public function __construct(FailureLogic $failureLogic,FailureValidation $failureValidation)
    {
        $this->failureLogic = $failureLogic;
        $this->failureValidation =$failureValidation;
    }
}
