<?php

/*
 * This file is part of the vartruexuan/easy-outapi.
 *
 * (c) vartruexuan <guozhaoxuanx@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vartruexuan\EasyOutApi\OutApi;


use Vartruexuan\EasyOutApi\Kernel\Support\Collection;

class RouteInfo extends Collection
{
    public $route='/';
    public $isMock=false;
    public $method='GET';
    public $mockValue;

    public function __construct(?Array $config)
    {
        parent::__construct($config);
        if($config){
            foreach ($config as $key=>$value){
                if(property_exists($this,$key)){
                    $this->{$key}=$value;
                }
            }
        }
    }
}