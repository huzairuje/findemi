<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:46
 */

namespace App\Services\Community;

use App\Models\Community;
use Illuminate\Http\Request;

class FindCommunityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Community();
    }

    public function findCommunityById($id)
    {
        $data = $this->model->find($id);
        return $data;
    }

    public function getAllCommunity()
    {
        $data = $this->model;
        return $data;
    }

}