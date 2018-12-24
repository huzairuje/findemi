<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 24/11/2018
 * Time: 20:55
 */

namespace App\Services\User;

use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginUserService
{
    public function loginUser(Request $request)
    {

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $response = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];

        return $response;
    }

}