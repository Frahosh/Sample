<?php

namespace Modules\UserAuth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\UserAuth\Authenticator\SessionAuth;
use Modules\UserAuth\Facades\AuthFacade;
use Modules\UserAuth\Facades\TokenGeneratorFacade;
use Modules\UserAuth\Facades\TokenSenderFacade;
use Modules\UserAuth\Facades\TokenStoreFacade;
use Modules\UserAuth\Facades\UserProviderFacade;
use Modules\UserAuth\Http\ResponderFacade;
use Modules\UserAuth\Http\Responses\ReactResponses;
use Modules\UserAuth\Services\FakeTokenGenerator;
use Modules\UserAuth\Services\FakeTokenSender;
use Modules\UserAuth\Services\FakeTokenStore;
use Modules\UserAuth\Services\TokenGenerator;
use Modules\UserAuth\Services\TokenSender;
use Modules\UserAuth\Services\TokenStore;
use Modules\UserAuth\Services\UserProvider;

class UserAuthServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\UserAuth\Http\Controllers';

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/user_auth_config.php',
            'user_auth_config'
        );
        AuthFacade::shouldProxyTo(SessionAuth::class);
        UserProviderFacade::shouldProxyTo(UserProvider::class);
        ResponderFacade::shouldProxyTo(ReactResponses::class);

        if(app()->runningUnitTests())
        {
            $tokenGenerator = FakeTokenGenerator::class;
            $tokenStore = FakeTokenStore::class;
            $tokenSender = FakeTokenSender::class;

        } else {
            $tokenGenerator = TokenGenerator::class;
            $tokenStore = TokenStore::class;
            $tokenSender = TokenSender::class;
        }
        TokenGeneratorFacade::shouldProxyTo($tokenGenerator);
        TokenStoreFacade::shouldProxyTo($tokenStore);
        TokenSenderFacade::shouldProxyTo($tokenSender);

    }
    public function boot()
    {
        $this->defineRoutes();
    }

    /**
     * @return void
     */
    public function defineRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/v1/')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/routes.php');
    }
}
