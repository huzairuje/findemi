<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Library\ApiResponseLibrary;
use App\Services\Post\CreatePostService;
use App\Services\Post\FindPostService;

class PostController extends Controller
{
    protected $apiLib;
    protected $createPostService;
    protected $findPostService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createPostService = new CreatePostService();
        $this->findPostService = new FindPostService();
    }

    /**
     * Get Detail Post Public when user searching on timeline.
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getPostPublic($id)
    {
        try {
            $data = $this->findPostService->findPostById($id);
            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $data = $this->createPostService->createPost($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
