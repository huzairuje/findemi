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
    protected $apiResponseLibrary;
    protected $createCommentService;
    protected $findCommentService;
    protected $updateCommentService;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                CreateCommentService $createCommentService,
                                FindCommentService $findCommentService,
                                UpdateCommentService $updateCommentService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->createCommentService = $createCommentService;
        $this->findCommentService = $findCommentService;
        $this->updateCommentService = $updateCommentService;
    }

    /**
     * Save user and get created by using Auth::id() facade.
     * @param CreateCommentRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CreateCommentRequest $request)
    {
        try {
            $data = $this->createCommentService->createComment($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
