<?php

namespace Modules\UserAuth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProvider
{
    public function getUserByLoginData($data)
    {
        $user = User::where('mobile', $data['mobile'])
//            ->where('api_token', $data['api_token'])
            ->first();
        if(Hash::check($data['password'], $user->password)){
            $user = [
                'id' => $user->id,
                'api_token' => $user->api_token,
                'name' => $user->name,
                'family' => $user->family,
                'mobile' => $user->mobile,
            ];
            return nullable($user);
        }


        return nullable(null);
    }
    public function getUserByData($key, $value)
    {
        $user = User::where($key, $value)->first();
        return nullable($user);
    }

    public function getUserByMobileBeforRegister($mobile)
    {
        return User::where('mobile', $mobile)->first();
    }

    public function isBanned($userId)
    {
        $user = User::find($userId) ?: new User;
        return $user->is_ban == 1 ? true : false;
    }
}
