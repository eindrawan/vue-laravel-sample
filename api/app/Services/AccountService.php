<?php

namespace App\Services;

use App\Account;
use App\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountService
{
    protected $account;
    public function __construct(Account $accountModel)
    {
        $this->account = $accountModel;
    }
    
    public function getDetails($id)
    {
        return Account::where('id',$id)->get();
    }

    public function getTransactions($id)
    {
        return Transaction::byAccount($id)->get();
    }

    public function transfer($from, $to, $amount, $details)
    {
        DB::beginTransaction();
        try{
            $affected = Account::debit($from, $amount);
            if($affected > 0){
                Account::credit($to, $amount);
                $res = Transaction::create(
                    [
                        'from' => $from,
                        'to' => $to,
                        'amount' => $amount,
                        'details' => $details
                    ]
                );
            } else {
                throw new Exception("Balance is not enough");
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}