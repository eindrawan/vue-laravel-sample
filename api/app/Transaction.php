<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'to', 'from', 'amount', 'details'
    ];

    public static function byAccount($id)
    {
        return Transaction::where('from',$id)
            ->orWhere('to',$id);
    }
}
