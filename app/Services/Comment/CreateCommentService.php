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

class CreateCommentService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Comment();
    }

    public function createComment(Request $request)
    {
        $data = $this->model;
        $data->name = $request->name;
        $data->title = $request->title;
        $data->text = $request->text;
        $data->post_id = $request->post_id;
        $data->parent_id = $request->parent_id;
        $data->created_by = auth()->user()->id;
        $data->save();

        return $data;
    }
}
