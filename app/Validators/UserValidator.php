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

    public function validateRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'full_name' => 'required|string',
            'phone' => 'unique:users|string',
            'gender' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        return $validator;

    }

    public function validateLogin(Request $request)
    {
        $validator = Validator::make($request->only(['email', 'password']), [
            'email' => 'required',
            'password' => 'required|string',
        ]);
        return $validator;
    }

    public function validateUpdateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'string|unique:users',
            'first_name' => 'string',
            'last_name' => 'string',
            'phone' => 'unique:users|string',
            'email' => 'string|email|unique:users',
            'password' => 'string|confirmed|min:6'
        ]);
        return $validator;
    }

}
