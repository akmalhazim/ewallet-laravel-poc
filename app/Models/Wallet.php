<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $appends = [
        'balance'
    ];

    protected $fillable = [
        'name'
    ];

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getBalanceAttribute()
    {
        $transactions = $this->transactions;

        return $transactions->reduce(function($acc, $txn) {
            if ($txn->op == 'debit') {
                return $acc - $txn->amount / 100;
            }
            return $acc + $txn->amount / 100;
        }, 0);
    }
}
