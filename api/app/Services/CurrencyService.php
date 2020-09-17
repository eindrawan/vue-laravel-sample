<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    
    public function getAll($id)
    {
        return DB::table('currencies')->get();;
    }

}