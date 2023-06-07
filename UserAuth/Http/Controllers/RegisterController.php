<?php

namespace Modules\UserAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Modules\UserAuth\Facades\AuthFacade;
use Modules\UserAuth\Facades\UserProviderFacade;
use Modules\UserAuth\Http\ResponderFacade;

class RegisterController extends Controller
{
    public function updateUser()
    {

        $apiToken = request()->api_token;

        //validate data
        $this->validateDataIsValid();

        //find user row in db or fail login
        $user = UserProviderFacade::getUserByData('api_token', $apiToken)->getOrSend(
            [ResponderFacade::class, 'userNotFound']
        );

        //update user
        $data = $this->prepareData();
        $user =AuthFacade::updateUserInRegister($data, $user)->getOrSend(
            [ResponderFacade::class, 'userAlreadyRegistered']
        );

//
//        $user =AuthFacade::loginUser($user)->getOrSend(
//            [ResponderFacade::class, 'userAlreadyRegistered']
//        );


        return ResponderFacade::LoggedIn($user);
    }
    private function validateDataIsValid()
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', 'max:255'],
            'family' => ['required', 'string', 'max:255'],
            'email' => ['required'],
            'city' => ['required'],
            'tell' => ['required'],
            'birth_day' => ['required'],
            'national_code' => ['required'],
            'password' => ['required', 'string', 'min:8'],
            'api_token' => ['required'],
        ]);
        if($validator->fails())
        {
            ResponderFacade::dataNotValid($validator->errors())->throwResponse();
        }
    }

    public function prepareData(): array
    {
        $data = [
            'name' => request()->name,
            'family' => request()->family,
            'email' => request()->email,
            'city' => request()->city,
            'tell' => request()->family,
            'birth_day' => request()->birth_day,
            'national_code' => request()->national_code,
            'password' => request()->password,
        ];
        return $data;
    }
}
