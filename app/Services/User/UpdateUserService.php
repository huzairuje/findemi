<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 19:15
 */

namespace App\Services\User;
use App\Models\User;


use Illuminate\Http\Request;

class UpdateUserService
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function update(Request $request)
    {
        $data = $this->model->findOrFail($request->user()->id);

        $data->username = $request->username;
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->password = bcrypt($request->password);

        $data->update();

        return $data;

    }

}