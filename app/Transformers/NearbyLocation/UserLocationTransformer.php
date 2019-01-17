<?php

namespace App\Transformers\NearbyLocation;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserLocationTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
//
        ];
    }

    public function transformWithLocation(User $user)
    {
        return [
            'lat' => (float)$user->lat,
            'lon' => (float)$user->lon
        ];
    }
}
