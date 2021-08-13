<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/7/12
 * Time: 17:05
 */


/**
 * 配置设计
 */

return [
    "baseUrl"=>"",
    "isMock"=>false,
    "route"=>[

        // routeKey
        ""=>[
            "route"=>"",
            "method"=>"",
            "isMock"=>false,
            "mockValue"=>"",
        ],

    ],

    /**
     * 接口请求相关配置，超时时间等，具体可用参数请参考：
     * http://docs.guzzlephp.org/en/stable/request-config.html
     *
     * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。
     * - retry_delay: 重试延迟间隔（单位：ms），默认 500
     * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */

    "http"=>[
        'max_retries' => 1, // 最大重试次数
        'retry_delay' => 500, // 重试间隔时间(毫秒)
        'timeout' => 5.0,// 超时（秒）
        // 'log_template'=>'', // 日志模板
    ],

    'log' => [
        'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path' => '*.log',
                'level' => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'daily',
                'path' => '*.log',
                'level' => 'info',
            ],
        ],
    ],
    'response_type'=>'array',// collection,raw,object

];