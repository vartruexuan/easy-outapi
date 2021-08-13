<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/8/13
 * Time: 16:12
 */

namespace Vartruexuan\EasyOutApi\Test;

use PHPUnit\Framework\TestCase;
use Vartruexuan\EasyOutApi\Kernel\Http\Response;

class OutApiTest extends TestCase
{

    public function testMock()
    {
        $mockValue = json_encode(
            [
                'rs' => 'testMock',
            ],
            true
        );
        $api = new TestApi(
            [
                'routes' => [
                    // 测试mock
                    'sendMock' => [
                        'isMock' => true,
                        'mockValue' => $mockValue,
                    ],
                ],

            ]
        );
        $response = $api->sendMock();
        $this->assertEquals($mockValue, $response->getBody());
    }


    public function testRequest()
    {
        $api = new TestApi();
        $response = $api->sendApi();
        $this->assertEquals('https://postman-echo.com/get', $response['url']);
    }

    public function testResponseType()
    {
        // array,collection,raw,object
        $api = new TestApi(
            [
                'response_type'=>'object',
            ]
        );
        $response1 = $api->sendApi();

        $api = new TestApi(
            [
                'response_type'=>'raw',
            ]
        );
        $response2 = $api->sendApi();
        $this->assertEquals(true, is_object($response1));
        $this->assertEquals(Response::class, get_class($response2));
    }

    public function testRouteParam()
    {
        // 测试路由参数
        $api = new TestApi();
        $this->assertEquals(
            'https://postman-echo.com/sendRouteParam/2',
            $api->getFullUrl(
                'sendRouteParam',
                [
                    'id' => 2,
                ]
            )
        );
    }


}