<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 04/11/2018
 * Time: 10:31
 */

namespace App\Services\Comment;

use App\Models\Comment;
use Illuminate\Http\Request;

class UpdateCommentService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Comment();
    }

    public function updateComment(Request $request)
    {
        //TODO
    }

}
