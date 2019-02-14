<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:23
 */

namespace App\Services\Activity;

use App\Models\Activity;
use App\Transformers\NearbyLocation\AllModuleNearbyLocationTransformer;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FindActivityService
{
    protected $model;
    protected $activityTransformer;

    public function __construct()
    {
        $this->model = new Activity();
        $this->activityTransformer = new AllModuleNearbyLocationTransformer();
    }

    public function findActivityById($activity_id)
    {
        $data = $this->model->find($activity_id);
        return $data;
    }

    public function getAllActivity()
    {
        $data = $this->model;
        $encodeData = $this->activityTransformer->transformAllModule($data);
        return $encodeData;
    }

    public function findActivityByUser()
    {
        $user = Auth::id();
        $createdBy = $this->model->where('created_by', $user)->firstOrFail();
        $data = (int)$createdBy['created_by'];

        if ($user == $data) {
            return $user;
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function findAllActivityByUser()
    {
        $user = Auth::id();
        $data = $this->model->where('created_by', $user);
        return $data;
    }

}
