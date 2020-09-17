<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CurrencyService;
use Validator;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CurrencyController extends Controller
{
    /**
     * @var currency
     */
    protected $currency;

    /**
     * PostController Constructor
     *
     * @param CurrencyService $service
     *
     */
    public function __construct(AccountService $service)
    {
        $this->currency = $service;
    }

    //
    public function getAll()
    {
        $currency = $this->currency->getAll($id);

        return currency;
    }
}
