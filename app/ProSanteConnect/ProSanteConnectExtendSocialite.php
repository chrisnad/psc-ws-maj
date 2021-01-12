<?php

namespace App\ProSanteConnect;

use SocialiteProviders\Manager\SocialiteWasCalled;

class ProSanteConnectExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('prosanteconnect', Provider::class);
    }
}
