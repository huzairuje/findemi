<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Activity;

use App\Library\ApiResponseLibrary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $apiLib;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;

    }

    public function index()
    {
        try {
            $activityAll = Activity::get();
            $return = $this->apiLib->singleData($activityAll, []);
            return response($return);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required|max:255',
                'start_date' => 'required',
                'end_date' => 'required',
                'address' => 'required|max:255',
                'tag' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response);
            }
            $data = $this->model;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->address = $request->address;
            $data->tag = $request->tag;

            $data->created_by = auth()->user()->id;
            $data->save();

            $return = $this->apiLib->singleData($data, []);
            return response($return);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response);
        }


    }

}