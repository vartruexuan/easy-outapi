<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/7/12
 * Time: 18:30
 */

namespace Vartruexuan\EasyOutApi\OutApi;

use Vartruexuan\EasyOutApi\Kernel\ServiceContainer;
use Vartruexuan\EasyOutApi\OutApi\Client\ServiceProvider;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

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


        if (!$this->isMock($routeKey)) {
            $response = $this->outApiClient->request($fullUrl, $routeInfo->method, $options ?? []);
        } else {
            // mock
            $response=new Response(200,[],$routeInfo->mockValue);
            $this->app->events->dispatch(new \Vartruexuan\EasyOutApi\Kernel\Events\HttpResponseCreated($response));
            $response=$this->outApiClient->castResponseToType($response, $this->config->get('response_type'));
        }
        return $this->dataFormat($response);
    }


    protected function setParam(?array $queryParam = null, ?array $param = null, ?array &$options = null)
    {
        if ($queryParam) {
            $options = array_merge(
                $options??[],
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
     * 获取完整url
     *
     * @param $routeKey
     * @param  array|null  $routeParam
     *
     * @return string
     */
    public function getFullUrl($routeKey, ?array $routeParam=null)
    {
        $routeInfo = $this->getConfig()['routes'][$routeKey] ?? [];
        $config = $this->getConfig();

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
     * @param  int  $retries retry num
     *
     * @return bool
     */
    abstract function isRetry(?Response $response,$retries=0): bool;

    /**
     * 数据解析映射
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
