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
use Illuminate\Support\Facades\DB;

class CreatePostService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    public function createPost (Request $request)
    {
        DB::beginTransaction();
        $data = $this->model;
        $data->name = $request->name;
        $data->title = $request->title;
        $data->text = $request->text;
        $data->community_id = (int)$request->community_id;
        $data->created_by = auth()->user()->id;
        $data->save();
        DB::commit();

        return $data;
    }
}