<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:34
 */

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunityValidator extends Validator
{
    public function validateCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'image_banner_url' => 'required',
            'base_camp_address' => 'required',
            'address' => 'required|max:255',
            'tag' => 'required|max:255',
            'lat' => 'required',
            'lon' => 'required',
            'address_from_map' => 'required',
        ]);

        return $validator;
    }

    public function validateUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'description' => 'max:255',
            'address' => 'max:255',
            'tag' => 'max:255',

        ]);

        return $validator;

    }

}