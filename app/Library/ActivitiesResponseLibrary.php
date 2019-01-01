<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 20/11/2018
 * Time: 17:38
 */

namespace App\Library;


class ActivitiesResponseLibrary
{
    public function successDeleteActivity()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.successDeletePost');
        return $return;
    }
}