<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:48
 */

namespace App\Services\Comment;

use App\Models\Comment;
use Illuminate\Http\Request;

class FindCommentService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Comment();
    }

    public function findById($id)
    {
        $data = $this->model->find($id);
        return $data;
    }
}
