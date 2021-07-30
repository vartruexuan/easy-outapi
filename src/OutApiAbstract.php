<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/7/12
 * Time: 18:30
 */

namespace Vartruexuan\EasyOutApi\OutApi;

use Vartruexuan\EasyOutApi\Kernel\ServiceContainer;
use Vartruexuan\EasyOutApi\Kernel\Traits\HasHttpRequests;
use Vartruexuan\EasyOutApi\Kernel\Events\HttpResponseCreated;

abstract class OutApiAbstract extends ServiceContainer
{

    use HasHttpRequests;

    protected $routes = [

        'routeKey' => [
            // 'route'=>'/test',
            // 'route'=>'/test/{userId}',
            'method' => 'GET',
            'mockValue' => '', // mock value
            'isMock' => true,
        ],

    ];

    protected $defaultConfig = [
        'baseUrl' => 'http://127.0.0.1', // domain
        'isMock' => false,
    ];


    protected function sendUrl(
        $routeKey,
        ?array $routeParam = null,
        ?array $queryParam = null,
        ?array $param = null,
        ?array $options = null,
        $isAsnyc = false
    ) {
        // route info
        $routeInfo = new RouteInfo($this->routes[$routeKey] ?? []);

        $this->setAuth($routeKey, $routeParam, $queryParam, $param, $options);

        $fullUrl = $this->getFullUrl($routeKey, $routeParam);

        $this->setParam($queryParam, $param, $options);
        // send
        $requestAction = $isAsnyc ? 'request' : 'requestAsnyc';
        if (!$this->isMock($routeKey)) {
            $response = $this->request($fullUrl,$routeInfo->method, $options);
        } else {
            // mock
            $response = $routeInfo->mockValue;
        }
        $this->events->dispatch(new HttpResponseCreated($response));

        return $this->dataFormat($response);
    }


    protected function setParam(?array $queryParam = null, ?array $param = null, ?array &$options = null)
    {
        if ($queryParam) {
            $options = array_merge(
                $options,
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
        if (isset($this->routes[$routeKey]['isMock'])) {
            $isMock = $this->routes[$routeKey]['isMock'];
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
    protected function getFullUrl($routeKey, ?array $routeParam)
    {
        $routeInfo = $this->routes[$routeKey] ?? [];
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
     * 额外操作
     *      可进行一些 sign|token 操作
     *      签名传输路径: header param
     *
     * @param $routeKey
     * @param  array|null  $routeParam
     * @param  array|null  $queryParam
     * @param  array|null  $param
     *
     * @return mixed
     */
    abstract function setAuth(
        $routeKey,
        ?array &$routeParam,
        ?array &$queryParam,
        ?array &$param,
        ?array &$options = null
    );

    /**
     * 数据解析映射
     *
     * @param $response
     *
     * @return array|mixed
     */
    protected function dataFormat($response)
    {
        return [];
    }
}
