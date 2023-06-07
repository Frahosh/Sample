<?php

namespace Modules\UserAuth\Services;

class TokenGenerator
{
    public function generateToken()
    {
        return random_int(100000, 1000000 - 1);
    }

}
