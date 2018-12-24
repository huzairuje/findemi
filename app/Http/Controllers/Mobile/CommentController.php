<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Services\Comment\CreateCommentService;
use App\Services\Comment\FindCommentService;
use App\Services\Comment\UpdateCommentService;


class CommentController extends Controller
{
    protected $apiLib;
    protected $createCommentService;
    protected $findCommentService;
    protected $updateCommentService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary();
        $this->createCommentService = new CreateCommentService();
        $this->findCommentService = new FindCommentService();
        $this->updateCommentService = new UpdateCommentService();
    }

    public function store(CreateCommentRequest $request)
    {
        try {
            $data = $this->createCommentService->createComment($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
