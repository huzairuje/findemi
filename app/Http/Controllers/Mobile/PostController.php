<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Library\ApiResponseLibrary;

use App\Services\Post\CreatePostService;
use App\Services\Post\FindPostService;

use App\Validators\PostValidator;

class PostController extends Controller
{
    protected $apiLib;
    protected $createPostService;
    protected $findPostService;
    protected $postValidator;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createPostService = new CreatePostService();
        $this->findPostService = new FindPostService();
        $this->postValidator = new PostValidator();

    }

    public function getPostPublic($id)
    {
        try {
            $data = $this->findPostService->findPostById($id);

            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);

            } else {
                $response = $this->apiLib->singleData($data, []);
                return response($response, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = $this->postValidator->validateStorePost($request);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);

            }

            $data = $this->createPostService->createPost($request);
            DB::commit();

            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }

    }

}