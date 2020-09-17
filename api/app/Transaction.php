<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'to', 'from', 'amount', 'details'
    ];
    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public static function byAccount($id)
    {
        return Transaction::where('from',$id)
            ->orWhere('to',$id);
    }
}
