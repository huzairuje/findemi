<?php

namespace App\Transformers\User;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform()
    {
        return [
            //
        ];
    }

    public function standarTransformer(User $user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'full_name' => $user->full_name,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'active' => $user->active,
            'created_at' => $user->created_at
        ];
    }

}
