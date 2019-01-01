<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\FindPostRequest;
use App\Library\PostResponseLibrary;
use App\Services\Mobile\Post\DeletePostService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Library\ApiResponseLibrary;
use App\Services\Post\CreatePostService;
use App\Services\Post\FindPostService;

class PostController extends Controller
{
    protected $apiLib;
    protected $postApiLib;
    protected $createPostService;
    protected $findPostService;
    protected $deletePostService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->postApiLib = new PostResponseLibrary();
        $this->createPostService = new CreatePostService();
        $this->findPostService = new FindPostService();
        $this->deletePostService = new DeletePostService();
    }

    /**
     * Get Detail Post Public when user searching on timeline.
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getPostPublic(FindPostRequest $request)
    {
        try {
            $data = $this->findPostService->findPostById($request->post_id);
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

    /**
     * store post on specific community.
     * @param CreatePostRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /** Delete Post
     * @param FindPostRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(FindPostRequest $request)
    {
        try {
            $data = $this->findPostService->findPostById($request->post_id);
            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $this->deletePostService->deletePost($request->post_id);
            $response = $this->postApiLib->successDeletePost();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
