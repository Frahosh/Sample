<?php

namespace Modules\UserAuth\Http\Responses;

use Illuminate\Http\Response;

class ReactResponses
{
    public function userAlreadyRegistered()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'user already registered'], Response::HTTP_BAD_REQUEST
        );
    }
    public function userCanNotLogin()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'user can not login'], Response::HTTP_BAD_REQUEST
        );
    }
    public function dataNotValid($errors)
    {
        return response()->json(
            ['status' => 'error',
                'message' => 'data is not valid',
                'data' => ['errors' => $errors]], Response::HTTP_BAD_REQUEST
        );
    }
    public function TokenIsValid($apiToken)
    {
        return response()->json(
            ['status' => 'success',
                'message' => 'your can send your name and family for registration',
                'data' => ['api_token' => $apiToken]], Response::HTTP_OK
        );
    }
    public function userAlreadyHasAccount($apiToken)
    {
        return response()->json(
            ['status' => 'error',
                'message' => 'user already has account then should enter name and family',
                'data' => ['api_token' => $apiToken]], Response::HTTP_OK
        );
    }
    public function TokenNotFound()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'token is not valid'], Response::HTTP_BAD_REQUEST
        );
    }
    public function LoggedIn($user)
    {
        return response()->json(
            ['status' => 'success',
                'message' => 'your are loggedIn',
                'data' => ['user' => $user]], Response::HTTP_OK
        );
    }
    public function blockedUser()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'you are blocked'], Response::HTTP_BAD_REQUEST
        );
    }

    public function tokenSent()
    {
        return response()->json(
            ['status' => 'success', 'message' => 'token was sent'], Response::HTTP_OK
        );
    }

    public function userNotFound()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'mobile does not exist'], Response::HTTP_BAD_REQUEST
        );
    }

    public function mobileNotValid()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'mobile is not valid'], Response::HTTP_BAD_REQUEST
        );
    }

    public function youShouldBeGuest()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'user is not guest'], Response::HTTP_BAD_REQUEST
        );
    }
}
