<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 20/11/2018
 * Time: 17:26
 */
namespace App\Library;

class UsersResponseLibrary
{
    protected $LIMIT = 10;

    public function unauthorizedEmailAndPassword()
    {
        $return = [];
        $return['meta']['status'] = 401;
        $return['meta']['message'] = trans('message.api.unauthorizedEmailAndPassword');
//        $return['data'] = $errors;
        return $return;
    }

    public function emailRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.emailRegistered');
//        $return['data'] = $errors;
        return $return;
    }

    public function emailIsAvailable()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.emailIsAvailable');
//        $return['data'] = $errors;
        return $return;
    }

    public function usernameRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.userNameRegistered');
//        $return['data'] = $errors;
        return $return;
    }

    public function usernameIsAvailable()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.userNameIsAvailable');
//        $return['data'] = $errors;
        return $return;
    }

    public function fullNameIsWrongFormat()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.fullNameIsWrongFormat');
//        $return['data'] = $errors;
        return $return;
    }

    public function fullNameIsOk()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.fullNameIsOk');
//        $return['data'] = $errors;
        return $return;
    }

    public function phoneIsWrongFormat()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.phoneIsWrongFormat');
//        $return['data'] = $errors;
        return $return;
    }

    public function phoneIsRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.phoneIsRegistered');
//        $return['data'] = $errors;
        return $return;
    }

    public function phoneIsOk()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.phoneIsOk');
//        $return['data'] = $errors;
        return $return;
    }

    public function genderIsRequired()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.genderIsRequired');
//        $return['data'] = $errors;
        return $return;
    }

    public function genderIsOk()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.genderIsOk');
//        $return['data'] = $errors;
        return $return;
    }

    public function passwordErrorResponse()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.passwordErrorResponse');
//        $return['data'] = $errors;
        return $return;
    }

    public function passwordOkrResponse()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.passwordOkResponse');
//        $return['data'] = $errors;
        return $return;
    }

    public function successLogout()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.successLogout');
        return $return;
    }


}