<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 19:48
 */

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class FindUserService
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findUserById($user_id)
    {
        $data = $this->model->find($user_id);
        return $data;
    }

    public function getAllUser()
    {
        $data = $this->model;
        return $data;

    }
}
