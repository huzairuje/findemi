<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:47
 */

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Http\Request;

class CreatePostService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Post();
    }
}