<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:40
 */

namespace App\Services\Community;

use App\Models\Community;
use Illuminate\Http\Request;

class UpdateCommunityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Community();
    }

    public function updateCommunity(Request $request, $id)
    {
        $data = $this->model->find($id);
        $data->name = $request->name;
        $data->description = $request->description;
        $data->address_from_map = $request->address_from_map;
        $data->is_public = $request->is_public;
        $data->tag = $request->tag;
        $data->lat = $request->lat;
        $data->lon = $request->lon;
        $data->address_from_map = $request->address_from_map;

        $data->update();

        return $data;
    }

}