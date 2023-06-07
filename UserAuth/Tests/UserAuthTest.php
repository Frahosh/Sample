<?php

namespace Modules\UserAuth\Tests;

use App\Models\User;
use Modules\UserAuth\Facades\AuthFacade;
use Modules\UserAuth\Facades\TokenGeneratorFacade;
use Modules\UserAuth\Facades\TokenSenderFacade;
use Modules\UserAuth\Facades\TokenStoreFacade;
use Modules\UserAuth\Facades\UserProviderFacade;
use Modules\UserAuth\Http\ResponderFacade;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    public function test_the_happy_path()
    {
        User::unguard();
        UserProviderFacade::shouldReceive('isBanned')
            ->once()
            ->with(1)
            ->andReturn(false);

        $user = new User(['id' => 1, 'mobile' => '09330945684']);
        UserProviderFacade::shouldReceive('getUserByMobile')
            ->once()
            ->with('09330945684')
            ->andReturn(nullable($user));

        TokenGeneratorFacade::shouldReceive('generateToken')
            ->once()
            ->withNoArgs()
            ->andReturn('1q2w3e');

        TokenStoreFacade::shouldReceive('saveToken')
            ->once()
            ->with('1q2w3e', $user->mobile);

        TokenSenderFacade::shouldReceive('send')
            ->once()
            ->with('1q2w3e', $user->mobile);

        ResponderFacade::shouldReceive('tokenSent')
            ->once();
        $this->get('/api/user-auth/request-token?mobile=09330945684');
    }

    public function test_user_is_banned()
    {
        User::unguard();
        UserProviderFacade::shouldReceive('isBanned')
            ->once()
            ->with(1)
            ->andReturn(true);

        $user = new User(['id' => 1, 'mobile' => '09330945684']);
        UserProviderFacade::shouldReceive('getUserByMobile')
            ->once()
            ->with('09330945684')
            ->andReturn(nullable($user));

        TokenGeneratorFacade::shouldReceive('generateToken')
            ->never();

        TokenSenderFacade::shouldReceive('send')->never();

        TokenStoreFacade::shouldReceive('saveToken')
            ->never();

        $response = $this->get('/api/user-auth/request-token?mobile=09330945684');
        $response->assertStatus(400);
        $response->assertJson(['status' => 'error', 'message' => 'you are blocked']);
    }

    public function test_mobile_dose_not_exist()
    {
        UserProviderFacade::shouldReceive('getUserByMobileBeforRegister')
            ->once()
            ->with('09330945684');

        UserProviderFacade::shouldReceive('isBanned')->never();
        TokenGeneratorFacade::shouldReceive('generateToken')->never();
        TokenSenderFacade::shouldReceive('send')->never();
        TokenStoreFacade::shouldReceive('saveToken')->never();
        ResponderFacade::shouldReceive('userAlreadyHasAccount')
            ->once()
            ->andReturn(response('hello'));
        $resp = $this->get('/api/user-auth/request-token?mobile=09330945684');
        $resp->assertSee('hello');
    }

    public function test_mobile_not_valid()
    {
        UserProviderFacade::shouldReceive('getUserByMobile')
            ->never();

        UserProviderFacade::shouldReceive('isBanned')->never();
        TokenGeneratorFacade::shouldReceive('generateToken')->never();
        TokenSenderFacade::shouldReceive('send')->never();
        TokenStoreFacade::shouldReceive('saveToken')->never();
        ResponderFacade::shouldReceive('mobileNotValid')
            ->once()
            ->andReturn(response('hello'));
        $resp = $this->get('/api/user-auth/request-token?mobile=0933945684');
        $resp->assertSee('hello');
    }

    public function test_user_is_guest()
    {
//        $this->withoutExceptionHandling();
        AuthFacade::shouldReceive('check')->once()->andReturn(true);
        UserProviderFacade::shouldReceive('getUserByMobile')
            ->never();

        UserProviderFacade::shouldReceive('isBanned')->never();
        TokenGeneratorFacade::shouldReceive('generateToken')->never();
        TokenSenderFacade::shouldReceive('send')->never();
        TokenStoreFacade::shouldReceive('saveToken')->never();
        ResponderFacade::shouldReceive('youShouldBeGuest')
            ->once()
            ->andReturn(response('hello'));
        $resp = $this->get('/api/user-auth/request-token?mobile=09330945684');
        $resp->assertSee('hello');
    }
}
