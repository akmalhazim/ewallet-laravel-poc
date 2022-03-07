<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('transactions')->get();

        return response()->json($wallets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $wallet = Wallet::create(
            $request->only('name')
        );

        return response()->json($wallet);
    }

    public function topup(Request $request, Wallet $wallet)
    {
        $request->validate([
            'amount' => 'required|int', // RM
        ]);

        $wallet->transactions()->create([
            'amount' => $request->amount * 100,
            'op' => 'credit', // topup
            'description' => 'Manual payment to admin'
        ]);

        return response()->json($wallet);
    }

    public function pay(Request $request, Wallet $wallet)
    {
        $request->validate([
            'amount' => 'required|int', // RM
            'description' => 'required|string'
        ]);

        $wallet->transactions()->create([
            'amount' => $request->amount * 100,
            'op' => 'debit', // topup
            'description' => $request->description
        ]);

        return response()->json($wallet);
    }
}
