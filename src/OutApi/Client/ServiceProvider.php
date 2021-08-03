<?php

namespace Vartruexuan\EasyOutApi\OutApi\Client;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 *
 * @package Vartruexuan\EasyOutApi\OutApi\Client
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['outApiClient'] = function ($app) {
            return new OutApiClient($app);
        };
    }
}
