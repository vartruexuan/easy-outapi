<?php

/*
 * This file is part of the vartruexuan/easy-outapi.
 *
 * (c) vartruexuan <guozhaoxuanx@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
