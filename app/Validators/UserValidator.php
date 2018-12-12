<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 18:55
 */

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidator extends Validator {

    public function validateEmailRegistration(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required|string|email|unique:users'
        ]);
        return $validator;
    }

    public function validateUsernameRegistration(Request $request)
    {
        $validator = Validator::make($request->only('username'), [
            'username' => 'required|string|unique:users'
        ]);
        return $validator;
    }

    public function validateFullNameRegistration(Request $request)
    {
        $validator = Validator::make($request->only('full_name'), [
            'full_name' => 'required|string'
        ]);
        return $validator;
    }

    public function validatePhoneRegistration(Request $request)
    {
        $validator = Validator::make($request->only('phone'), [
            'phone' => 'unique:users|string'
        ]);
        return $validator;
    }
}
