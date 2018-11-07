<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 17/10/2018
 * Time: 13:21
 */

namespace App\Library;


class ApiResponseLibrary
{
    protected $LIMIT = 10;
    public function listPaginate($collection)
    {
        $return = [];
        $paginated = $collection->paginate($this->LIMIT);
        $return['meta']['error'] = 0;
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.success');
        $return['meta']['total'] = $paginated->total();
        $return['meta']['per_page'] = $paginated->perPage();
        $return['meta']['current_page'] = $paginated->currentPage();
        $return['meta']['last_page'] = $paginated->lastPage();
        $return['meta']['has_more_pages'] = $paginated->hasMorePages();
        $return['meta']['from'] = $paginated->firstItem();
        $return['meta']['to'] = $paginated->lastItem();
        $return['links']['self'] = url()->full();
        $return['links']['next'] = $paginated->nextPageUrl();
        $return['links']['prev'] = $paginated->previousPageUrl();
        $return['data'] = $paginated->items();
        return $return;
    }

    public function singleData($data, array $relations)
    {
        $return = [];
        $return['meta']['error'] = 0;
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.success');
        $return['data'] = $data;
        $return = $this->generateRelations($return, $relations);
        return $return;
    }

    private function generateRelations($return, $relations) {
        if (isset($relations)) {
            foreach ($relations as $key => $relation)
            {
                $return['data'][$key] = $relation;
            }
        }
        return $return;
    }

    public function successResponse($id)
    {
        $return = [];
        $return['meta']['error'] = 0;
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.success');
        $return['data']['id'] = $id;
        return $return;
    }

    public function errorResponse(\Exception $e)
    {
        $return = [];
        $return['meta']['status'] = 500;
        $return['meta']['message'] = trans('message.api.error');
        $return['meta']['error'] = $e->getMessage();
        return $return;
    }

    public function notFoundResponse()
    {
        $return = [];
        $return['meta']['status'] = 404;
        $return['meta']['message'] = trans('message.api.notFound');
//        $return['data'] = $errors;
        return $return;
    }

    public function validationFailResponse($errors)
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.badRequest');
        $return['data'] = $errors;
        return $return;
    }

    public function unauthorizedResponse($errors)
    {
        $return = [];
        $return['meta']['status'] = 401;
        $return['meta']['message'] = trans('message.api.unauthorized');
        $return['data'] = $errors;
        return $return;
    }

    public function unauthorizedEmailAndPassword($errors)
    {
        $return = [];
        $return['meta']['status'] = 401;
        $return['meta']['message'] = trans('message.api.unauthorizedEmailAndPassword');
        $return['data'] = $errors;
        return $return;
    }

    public function badRequest($errors)
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.badRequest');
        $return['data'] = $errors;
        return $return;
    }

    public function invalidToken($errors)
    {
        $return = [];
        $return['meta']['status'] = 401;
        $return['meta']['message'] = trans('message.api.invalidToken');
        $return['data'] = $errors;
        return $return;
    }

    public function successLogout()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.successLogout');
        return $return;
    }

    public function emailRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.emailRegistered');
//        $return['data'] = $errors;
        return $return;
    }

    public function emailIsAvailable()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.emailIsAvailable');
//        $return['data'] = $errors;
        return $return;
    }

    public function usernameRegistered()
    {
        $return = [];
        $return['meta']['status'] = 400;
        $return['meta']['message'] = trans('message.api.userNameRegistered');
//        $return['data'] = $errors;
        return $return;
    }

    public function usernameIsAvailable()
    {
        $return = [];
        $return['meta']['status'] = 200;
        $return['meta']['message'] = trans('message.api.userNameIsAvailable');
//        $return['data'] = $errors;
        return $return;
    }

}