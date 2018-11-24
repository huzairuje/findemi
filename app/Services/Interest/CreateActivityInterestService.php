<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 21:22
 */

namespace App\Services\Interest;

use App\Models\Interest;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateActivityInterestService
{
    protected $model;
    protected $activityModel;

    public function __construct()
    {
        $this->model = new Interest();
        $this->activityModel = new Activity();
    }

    public function createActivityInterest(Request $request)
    {
        DB::beginTransaction();
        $user = $this->activityModel->findOrFail($request['activity_id']);
        $data = $this->model->findOrFail($request['interest_id']);

        $data->user()->attach($user);
        DB::commit();

        return $data;
    }
}