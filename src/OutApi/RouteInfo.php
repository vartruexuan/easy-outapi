<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/7/30
 * Time: 11:01
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