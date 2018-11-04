<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:48
 */

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Http\Request;

class FindPostService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    public function findPostById($id)
    {
        $data = $this->model->find($id);
        return $data;
    }
}