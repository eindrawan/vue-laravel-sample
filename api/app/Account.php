<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Model implements JWTSubject
{
    protected $fillable = [
        'name', 'balance'
    ];
    
    protected $hidden = [
        'updated_at', 'created_at'
    ];

    /**
     * Implementation of JWTSubject
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function debit($id, $amount)
    {
        $affected = Account::where('id', $id)
            ->where('balance','>=',$amount)
            ->update(['balance' => DB::raw('balance -' . $amount)]);

        return $affected;
    }

    public static function credit($id, $amount)
    {
        return Account::where('id', $id)
            ->update(['balance' => DB::raw('balance +' . $amount)]);
    }
}
