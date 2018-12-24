<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 15:34
 */

namespace App\Services\Interest;

use App\Models\Interest;

class FindInterestService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Interest();
    }

    public function findInterestById($id)
    {
        $data = $this->model->find($id);
        return $data;
    }

    public function getAllInterest()
    {
        $data = $this->model;
        return $data;
    }
}