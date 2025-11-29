<?php
namespace App\Services;

use App\Models\TokenWallet;
use App\Models\TokenUsageLog;
use Illuminate\Support\Facades\Auth;

class TokenService
{
    public function checkTokens($requiredTokens)
    {
        $wallet = TokenWallet::where('user_id', Auth::id())->first();

        if (!$wallet || $wallet->total_token_available < $requiredTokens) {
            return false;
        }

        return true;
    }

    public function deductTokens($source, $usedTokens, $meta = [])
    {
        $wallet = TokenWallet::where('user_id', Auth::id())->first();
        if(!$wallet || $wallet->total_token_available < $usedTokens) {
            return false;
        }

        // Deduct tokens
        $wallet->total_token_available -= $usedTokens;
        $wallet->total_token_used += $usedTokens;
        $wallet->save();

        // Log usage
        TokenUsageLog::create([
            'user_id'     => Auth::id(),
            'source'      => $source,
            'token_used'  => $usedTokens,
            'request_meta'=> json_encode($meta)
        ]);

        return true;
    }
}
