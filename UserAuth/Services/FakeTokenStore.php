<?php

namespace Modules\UserAuth\Services;

class FakeTokenStore
{
    public function saveToken($token, $userId)
    {
        cache()->set($token . '_user_auth', $userId, 120);
    }
}
