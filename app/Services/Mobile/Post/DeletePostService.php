<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 02/01/2019
 * Time: 0:51
 */

namespace App\Services\Mobile\Post;

use App\Http\Requests\Post\FindPostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeletePostService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    public function deletePost(FindPostRequest $request)
    {
        DB::beginTransaction();
        $data = $this->model->find($request->input('post_id'));
        $data->delete();
        DB::commit();
        return $data;

    }

}