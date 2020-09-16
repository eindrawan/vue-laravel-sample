<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'balance'
    ];

    public static function debit($id, $amount)
    {
        // DB::enableQueryLog(); // Enable query log
        $affected = Account::where('id', $id)
            ->where('balance','>=',$amount)
            ->update(['balance' => DB::raw('balance -' . $amount)]);

        // var_dump(DB::getQueryLog()); // Show results of log
        return $affected;
    }

    public static function credit($id, $amount)
    {
        return Account::where('id', $id)
            ->update(['balance' => DB::raw('balance +' . $amount)]);
    }
}
