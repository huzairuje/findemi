<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:23
 */

namespace App\Services\Activity;

use App\Models\Activity;

class FindActivityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Activity();
    }

    public function findActivity($id)
    {
        $data = $this->model->find($id);
        return $data;
    }

    public function getAllActivity()
    {
        $data = $this->model->get();
        return $data;
    }

}