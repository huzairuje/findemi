<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 17:35
 */


namespace App\Services\User;

use App\Http\Requests\User\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Notifications\SignUpActivate;

class CreateUserService {

    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function create(CreateUserRequest $request)
    {
        DB::beginTransaction();
        $data = $this->model;
        $data->username = $request->username;
        $data->full_name = $request->full_name;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->password = bcrypt($request->password);
        $data->activation_token = str_random(60);

        $data->save();
        /*
         * commended because this method need validation domain on server.
         */
//        $data->notify(new SignupActivate($data));

        DB::commit();
        return $data;

    }

}