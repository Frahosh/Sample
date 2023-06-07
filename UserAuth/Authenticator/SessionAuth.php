<?php

namespace Modules\UserAuth\Authenticator;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\UserDetail\Database\Models\UserDetail;

class SessionAuth
{
    public function check()
    {
        return Auth::check();
    }
    public function createUserByMobile($mobile)
    {
        //create user
        $user = User::create([
            'mobile' => $mobile,
            'api_token' => Str::random(100),
        ]);
        return $user->api_token;
    }

    public function updateUserInRegister($data, $user)
    {
        if(! isset($user->userDetail->id))
        {
            $user->update([
                'name' => $data['name'],
                'family' => $data['family'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $userDetail = UserDetail::create([
                'city' => $data['city'],
                'tell' => $data['tell'],
                'birth_day' => $data['birth_day'],
                'national_code' => $data['national_code'],
                'user_id' => $user->id,

            ]);
            $user = [
                'api_token' => $user->api_token,
                'name' => $user->name,
                'family' => $user->family,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'city' => $userDetail->city,
                'tell' => $userDetail->tell,
                'birth_day' => $userDetail->birth_day,
                'national_code' => $userDetail->national_code,

            ];
            return nullable($user);

        }
        return nullable(null);
    }

    public function loginUser($user)
    {
        if(!auth()->attempt($user))
        {
            return nullable(null);
        }
        return nullable($user);
    }
}
