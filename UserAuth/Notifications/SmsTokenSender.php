<?php

namespace Modules\UserAuth\Notifications;

use Cryptommer\Smsir\Classes\Send;
use Cryptommer\Smsir\Classes\Smsir;
use Cryptommer\Smsir\Objects\Parameters;

class SmsTokenSender
{
    public static function sendOtpCode($mobile, $token)
    {
        $templateId = config('user_auth_config.TEMPLATE_ID');
        $send = self::getObjectSmsIr();
        $parameters = self::makeArrayParameters($token);
        $send->Verify($mobile, $templateId, $parameters);

    }

    /**
     * @return Parameters[]
     * @param $token
     */
    public static function makeArrayParameters($token): array
    {
        $parameter = new Parameters('code', $token);
        return array($parameter);
    }

    /**
     * @return Send
     */
    public static function getObjectSmsIr(): Send
    {
        $lineNumber= config('user_auth_config.SMS_IR_LINE_NUMBER');
        $apiKey = config('user_auth_config.SMS_IR_API_KEY');
        $smsIr = new Smsir($lineNumber, $apiKey);
        return $smsIr->Send();
    }
}
