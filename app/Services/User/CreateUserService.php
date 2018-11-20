<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 17:35
 */


namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\Request;

class CreateUserService {

    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function create(Request $request)
    {
        $data = $this->model;
        $data->username = $request->username;
        $data->full_name = $request->full_name;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->password = bcrypt($request->password);
//        $data->activation_token = str_random(60);

        $data->save();

        return $data;

    }

}