<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:23
 */

namespace App\Services\Event;

use App\Models\Event;

class FindEventService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Event();
    }

    public function findEvent($id)
    {
        $data = $this->model->find($id);
        return $data;
    }

    public function getAllEvent()
    {
        $data = $this->model->get();
        return $data;
    }

}