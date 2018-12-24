<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 25/11/2018
 * Time: 9:54
 */

namespace App\Services\User;
use App\Models\User;


class SignUpActivateService
{
    public function activateUser($token)
    {
        $user = User::where('activation_token', $token)->first();
        return $user;
    }
}