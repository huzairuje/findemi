<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:40
 */

namespace App\Services\Community;

use App\Http\Requests\Community\UpdateCommunityRequest;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateCommunityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Community();
    }

    public function updateCommunity(UpdateCommunityRequest $request)
    {
        DB::beginTransaction();
        $data = $this->model->find($request->input('community_id'));
        $data->name = $request->name;
        $data->description = $request->description;
        $data->address_from_map = $request->address_from_map;
        $data->is_public = $request->is_public;
        $data->tag = $request->tag;
        $data->lat = (float)$request->lat;
        $data->lon = (float)$request->lon;
        $data->address_from_map = $request->address_from_map;
        $data->update();
        DB::commit();
        return $data;
    }

}