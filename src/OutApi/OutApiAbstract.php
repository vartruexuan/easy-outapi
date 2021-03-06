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

use Vartruexuan\EasyOutApi\Kernel\ServiceContainer;
use Vartruexuan\EasyOutApi\OutApi\Client\ServiceProvider;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

/**
 * Class OutApiAbstract
 *
 * @property \Vartruexuan\EasyOutApi\OutApi\Client\OutApiClient $outApiClient
 * @package Vartruexuan\EasyOutApi\OutApi
 */
abstract class OutApiAbstract extends ServiceContainer
{
    protected $providers = [
        ServiceProvider::class,
    ];
    protected $defaultConfig = [
        'baseUrl' => 'http://127.0.0.1', // domain
        'isMock' => false,
        'routes' => [
            'routeKey' => [
                // 'route'=>'/test',
                // 'route'=>'/test/{userId}',
                'method' => 'GET',
                'isMock' => true,
                'mockValue' => '', // mock value
            ],
        ],
    ];

    protected function request($routeKey, ?array $routeParam = null, ?array $queryParam = null, ?array $param = null, ?array $options = null, $isAsnyc = false)
    {
        // route info
        $routeInfo = new RouteInfo($this->getConfig()['routes'][$routeKey] ?? []);

        $fullUrl = $this->getFullUrl($routeKey, $routeParam);

        $this->setParam($queryParam, $param, $options);

        // mock
        if ($this->isMock($routeKey)) {
            $mockHandler=$routeInfo['mockValue'] ;
            if(!$mockHandler instanceof MockHandler){
                $mockHandler=new MockHandler();
            }
            $this->guzzle_handler=$mockHandler;
        }

        $response = $this->outApiClient->request($fullUrl, $routeInfo->method, $options ?? []);

        return $this->dataFormat($response);
    }


    protected function setParam(?array $queryParam = null, ?array $param = null, ?array &$options = null)
    {
        if ($queryParam) {
            $options = array_merge(
                $options ?? [],
                [
                    'query' => $queryParam,
                ]
            );
        }
    }

    /**
     * is mock request
     *
     * @param $routeKey
     *
     * @return false|mixed
     */
    protected function isMock($routeKey)
    {
        // route > config
        $config = $this->getconfig();
        $isMock = $config['isMock'] ?? false;
        if (isset($config['routes'][$routeKey]['isMock'])) {
            $isMock = $config['routes'][$routeKey]['isMock'];
        }

        return $isMock;
    }

    /**
     * ????????????url
     *
     * @param $routeKey
     * @param  array|null  $routeParam
     *
     * @return string
     */
    public function getFullUrl($routeKey, ?array $routeParam = null)
    {
        $config = $this->getConfig();
        $routeInfo = $config['routes'][$routeKey] ?? [];
        $url = $config['baseUrl'].$routeInfo['route'];

        // replace route param
        if ($routeParam) {
            $paramsNames = array_map(
                function ($n) {
                    return "/".preg_quote('{'.$n.'}')."/";
                },
                array_keys($routeParam)
            );
            $url = preg_replace($paramsNames, array_values($routeParam), $url);
        }

        return $url;
    }

    /**
     * @param  \GuzzleHttp\Psr7\Request  $request
     * @param  array  $options
     *
     * @return mixed
     */
    abstract function beforeAction(Request $request, &$options = []);

    /**
     * is retry
     *
     * @param  \GuzzleHttp\Psr7\Response|null  $response
     * @param  int  $retries  retry num
     *
     * @return bool
     */
    abstract function isRetry(?Response $response, $retries = 0): bool;

    /**
     * ??????????????????
     *
     * @param $response
     *
     * @return array|mixed
     */
    protected function dataFormat($response)
    {
        return $response;
    }

}
