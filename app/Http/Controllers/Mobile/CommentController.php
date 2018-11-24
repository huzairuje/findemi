<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

use App\Services\Comment\CreateCommentService;
use App\Services\Comment\FindCommentService;
use App\Services\Comment\UpdateCommentService;

use App\Validators\CommentValidator;

class CommentController extends Controller
{
    protected $apiLib;
    protected $commentValidator;
    protected $createCommentService;
    protected $findCommentService;
    protected $updateCommentService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary();
        $this->createCommentService = new CreateCommentService();
        $this->findCommentService = new FindCommentService();
        $this->updateCommentService = new UpdateCommentService();
        $this->commentValidator = new CommentValidator();
    }

    public function store(Request $request)
    {
        try {
            $validator = $this->commentValidator->validateStoreComment($request);
            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->createCommentService->createComment($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

}
