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
use Exception;

class PostController extends Controller
{
    protected $apiResponseLibrary;
    protected $postResponseLibrary;
    protected $createPostService;
    protected $findPostService;
    protected $deletePostService;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                PostResponseLibrary $postResponseLibrary,
                                CreatePostService $createPostService,
                                FindPostService $findPostService,
                                DeletePostService $deletePostService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->postResponseLibrary = $postResponseLibrary;
        $this->createPostService = $createPostService;
        $this->findPostService = $findPostService;
        $this->deletePostService = $deletePostService;
    }

    /**
     * Get Detail Post Public when user searching on timeline.
     * @param $request
     * @return response
     */
    public function getPostPublic(FindPostRequest $request)
    {
        try {
            $data = $this->findPostService->findPostById($request->input('post_id'));
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * store post on specific community.
     * @param CreatePostRequest $request
     * @return response
     */
    public function store(CreatePostRequest $request)
    {
        try {
            $data = $this->createPostService->createPost($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** Delete Post
     * @param FindPostRequest $request
     * @return response
     */
    public function delete(FindPostRequest $request)
    {
        try {
            $data = $this->findPostService->findPostById($request->input('post_id'));
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $this->deletePostService->deletePost($request);
            $response = $this->postResponseLibrary->successDeletePost();
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
