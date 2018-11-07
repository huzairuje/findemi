<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 21:22
 */

namespace App\Services\Interest;

use App\Models\Interest;
use App\Models\Community;
use Illuminate\Http\Request;

class CreateCommunityInterestService
{
    protected $model;
    protected $communityModel;

    public function __construct()
    {
        $this->model = new Interest();
        $this->communityModel = new Community();
    }

    public function createCommunityInterest(Request $request)
    {
        $community = $this->communityModel->findOrFail($request['community_id']);
        $data = $this->model->findOrFail($request['interest_id']);

        $data->user()->attach($community);

        return $data;
    }
}