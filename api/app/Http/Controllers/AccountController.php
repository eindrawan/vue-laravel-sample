<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AccountService;
use Validator;
use Exception;

class AccountController extends Controller
{
    /**
     * @var account
     */
    protected $account;

    /**
     * PostController Constructor
     *
     * @param AccountService $accountService
     *
     */
    public function __construct(AccountService $accountService)
    {
        $this->account = $accountService;
    }

    //
    public function index($id)
    {
        $account = $this->account->getDetails($id);

        return response()->json([
            'success' => true,
            'data' => $account
        ]);     
    }

    public function transactions($id){
        $account = $this->account->getTransactions($id);

        return response()->json([
            'success' => true,
            'data' => $account
        ]);
    }

    public function transfer(Request $request, $id){
        // validator
        $params = $request->all();
        $errors = $this->validateData($params, [
            'to' => 'required|numeric|min:1|not_in:'.$id,
            'amount' => 'required|numeric|min:0',
            'details' => 'nullable|string'
        ], [
            'to.required' => 'Please enter destination account id',
            'to.not_in' => 'Destination account id cannot be the same as origin',
            'to.numeric' => 'Destination account id should be numeric',
            'to.min' => 'Destination account id should be larger than 1'
        ]);

        // check validation
        if ($errors) return $errors;
        try{
            $this->account->transfer($id, $params['to'], $params['amount'], $params['details']);
            return response()->json([
                'success' => true,
                'message' => 'Payment success'
            ]);
        } catch(Exception $ex) {
            return response()->json([
                'success' => false,
                'error' => $ex->getMessage()
            ]);
        }
        
    }
}
