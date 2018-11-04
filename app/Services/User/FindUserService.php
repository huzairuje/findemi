<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 19:48
 */

namespace App\Services\User;

use App\Models\User;

class FindUserService
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function findUserById($id)
    {
        $data = $this->model->find($id);
        return $data;
    }
}