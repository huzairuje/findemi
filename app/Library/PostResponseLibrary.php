<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 02/01/2019
 * Time: 0:58
 */

namespace App\Library;


class PostResponseLibrary
{
    public function successDeletePost()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.successDeletePost');
        return $return;
    }

    public function successPost()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.successPost');
        return $return;
    }

}