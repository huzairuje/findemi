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
        return $return;
    }

    public function userIsNotActive()
    {
        $return = [];
        $return['meta']['status'] = 401;
        $return['meta']['message'] = trans('message.api.userIsNotActive');
        return $return;
    }

    public function userIsBlocked()
    {
        $return = [];
        $return['meta']['status'] = 401;
        $return['meta']['message'] = trans('message.api.userIsBlocked');
        return $return;
    }

    public function emailRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.emailRegistered');
        return $return;
    }

    public function emailIsAvailable($data)
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.emailIsAvailable');
        $return['data'] = $data;
        return $return;
    }

    public function usernameRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.userNameRegistered');
        return $return;
    }

    public function usernameIsAvailable($data)
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.userNameIsAvailable');
        $return['data'] = $data;
        return $return;
    }

    public function fullNameIsWrongFormat()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.fullNameIsWrongFormat');
        return $return;
    }

    public function fullNameIsOk($data)
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.fullNameIsOk');
        $return['data'] = $data;
        return $return;
    }

    public function phoneIsWrongFormat()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.phoneIsWrongFormat');
        return $return;
    }

    public function phoneIsRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.phoneIsRegistered');
        return $return;
    }

    public function phoneIsOk($data)
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.phoneIsOk');
        $return['data'] = $data;
        return $return;
    }

    public function genderIsRequired()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.genderIsRequired');
        return $return;
    }

    public function genderIsOk()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.genderIsOk');
        return $return;
    }

    public function passwordErrorResponse()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.passwordErrorResponse');
        return $return;
    }

    public function passwordOkeResponse()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.passwordOkResponse');
        return $return;
    }

    public function successLogout()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.successLogout');
        return $return;
    }

    public function successLogin($data)
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.login');
        $return['data'] = $data;
        return $return;
    }

    public function successRegister($data)
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.register');
        $return['data'] = $data;
        return $return;
    }

}