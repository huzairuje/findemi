<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 02/01/2019
 * Time: 1:17
 */

namespace App\Services\Mobile\Activity;

use App\Http\Requests\Activity\FindActivityRequest;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class DeleteActivityService
{
    protected $model;

    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    public function deleteActivity(FindActivityRequest $request)
    {
        DB::beginTransaction();
        $data = $this->model->find($request->input('activity_id'));
        $data->delete();
        DB::commit();
        return $data;
    }

}
