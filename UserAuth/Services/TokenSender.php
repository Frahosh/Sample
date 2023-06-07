<?php

namespace Modules\UserAuth\Services;

use Modules\UserAuth\Notifications\SmsTokenSender;

class TokenSender
{
    public function send($token, $mobile)
    {
        SmsTokenSender::sendOtpCode($mobile, $token);
    }
}
