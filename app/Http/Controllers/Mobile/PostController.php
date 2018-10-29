<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Library\ApiResponseLibrary;

class PostController extends Controller
{
    protected $apiLib;
    protected $model;
    protected $commentModel;
    protected $communityPostModel;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->model = new Post();
        $this->commentModel = new Comment();

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'title' => 'required|max:255',
                'text' => 'max:255',

            ]);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);

            }

            $data = $this->model;
            $data->name = $request->name;
            $data->title = $request->title;
            $data->text = $request->text;
            $data->community_id = $request->community_id;
            $data->created_by = auth()->user()->id;
            $data->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }

    }

}
