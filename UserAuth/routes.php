<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Modules\UserAuth\Facades\TokenSenderFacade;
use Modules\UserAuth\Facades\TokenStoreFacade;

Route::post('/user-auth/request-token', 'TokenController@issueToken');

Route::post('/user-auth/check-token', 'TokenController@checkToken');

Route::post('/user-auth/update-user', 'RegisterController@updateUser');

Route::post('/user-auth/user-login', 'LoginController@userLogin');

Route::post('/user-auth/logout', 'LoginController@logout');

