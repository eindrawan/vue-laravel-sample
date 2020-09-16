<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function validateData($d, $p, $m){
        $validator = Validator::make($d, $p, $m);
        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'error'=>$validator->messages()
            ]);
        }
        return null;
    }
}
