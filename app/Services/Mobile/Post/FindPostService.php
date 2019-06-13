<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:48
 */

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FindPostService
{
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function findPostById($post_id)
    {
        $data = $this->model->find($post_id);
        return $data;
    }

    public function getAllPost()
    {
        $data = $this->model;
        return $data;
    }

    public function findAllPostByUser()
    {
        $user = Auth::id();
        $data = $this->model->where('created_by', $user);
        return $data;
    }

}
