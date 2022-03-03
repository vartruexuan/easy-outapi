
<h1 align="center"> easy-outapi </h1>  
  
<p align="center"> .</p>  
  
<div align="center">
[![Tests](https://github.com/vartruexuan/easy-outapi/actions/workflows/tests.yml/badge.svg)](https://github.com/vartruexuan/easy-outapi/actions/workflows/tests.yml)
</div>

## 安装  
  
```shell  
$ composer require vartruexuan/easy-outapi  
```  
## 设计该组件初衷
  项目中,接入第三方接口，没有特定规范.  总是出现每个人写一套curl,且功能不齐全,自己写重试逻辑  无统一规范管理,造成代码冗余,出现很多业务问题,不易定位问题。此组件希望解决对应问题,统一规范管理,实际开发只需关注自己的业务开发。
  
## 支持  
 + 1.规范curl  
 + 2.兼容restful api  
 + 2.自动记录日志,支持日志模板配置  
 + 3.兼容mock  
 + 4.支持接口重试配置  
## 使用  
+ 配置 (配置优先级 > 类中defaultConfig )
```php
<?php  
return [  
  "baseUrl"=>"",  
  "isMock"=>false,  
  "route"=>[  
	  "firstApi"=>[   
		  "isMock"=>false,  
	  ],  
  ],  
  
  /**  
  * 接口请求相关配置，超时时间等，具体可用参数请参考：  
  * http://docs.guzzlephp.org/en/stable/request-config.html  
  * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。  
  * - retry_delay: 重试延迟间隔（单位：ms），默认 500  
  * - log_template: 指定 HTTP 日志模板，请参考：	https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php  
 */  
  "http"=>[  
	  'max_retries' => 1, // 最大重试次数  
	  'retry_delay' => 500, // 重试间隔时间(毫秒)  
	  'timeout' => 5.0,// 超时（秒）  
	  // 'log_template'=>'', // 日志模板  
  ],  
  'log' => [  
	  'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod  'channels' => [  
	  // 测试环境  
	  'dev' => [  
	    'driver' => 'single',  
	    'path' => '*.log', // 日志路径
	    'level' => 'debug',// 日志等级
	  ],  
	  // 生产环境  
	  'prod' => [  
	    'driver' => 'daily',  
	    'path' => '*.log',  
	    'level' => 'info',  
	  ],  
  ],  
  'response_type'=>'array',// 数据返回类型 collection,raw,object  
];

```

+ 编写第三方类，继承 OutApiAbstract  
```php
/**
* 测试 api
**/
class TestApi extends OutApiAbstract
{
}  
```
+ 写入默认配置
```php
class TestApi extends OutApiAbstract
{
	  protected $defaultConfig = [  
	  // 域名配置
	  'baseUrl' => 'https://postman-echo.com',
	  // 路由配置(下面说明)
	  'routes'=>[],
	 // ... 
	 // 此处也可以写一些业务参数，比如签名配置(beforeAction()可做签名操作,见下面说明 )
  ];
} 
```
+ 编写第一个api
```php
class TestApi extends OutApiAbstract
{
	  protected $defaultConfig = [  
	  // 域名配置
	  'baseUrl' => 'https://postman-echo.com',
	  // 路由配置(下面说明)
	  'routes'=>[
		   // 第一个接口
		  'firstApi'=>[
			  'route'=>'/index/first-api',
			  'method'=>'GET',
		  ],
	  ],
	 // ... 
	 // 此处也可以写一些业务参数，比如签名配置(beforeAction()可做签名操作,见下面说明 )
  ];
	// 使用 (new TestApi($config))->firstApi();
	public function firstApi($params=[])
	{
		// 无参数路由
		return $this->request('firstApi');
	}
} 
```
+ 多种路由配置（此处略去类其它说明）
```php
	  protected $defaultConfig = [  
	  // 域名配置
	  'baseUrl' => 'https://postman-echo.com',
	  // 路由配置(下面说明)
	  'routes'=>[
		   // 第一个接口
		  'firstApi'=>[
			  'route'=>'/index/first-api',
			  'method'=>'GET',
		  ],
		  // restful api (实际传参查看request())
		   'restfulApi'=>[
			  'route'=>'/index/get-user-info/{userId}',
			  'method'=>'GET',
		  ],
		   // mock
		   'mockApi'=>[
			  'route'=>'/index/first-api',
			  'method'=>'GET',
			   /**  
				* 实际场景应该写到配置里  
				* @see https://guzzle-cn.readthedocs.io/zh_CN/latest/testing.html#mock-handler  
				*/
			  'isMock'=>true,
			  'mockValue'=>new MockHandler(  [
					    new \GuzzleHttp\Psr7\Response(200, [], json_encode(['code'=>0,'data'=>['a']])),  
					    new \GuzzleHttp\Psr7\Response(202, [],json_encode(['code'=>0,'data'=>['a']])),  
					   new RequestException("Error Communicating with Server", new Request('GET','test')),  
	  ]  
			)
		  ],
	  ],
	 // ... 
	 // 此处也可以写一些业务参数，比如签名配置(beforeAction()可做签名操作,见下面说明 )
  ];
```
+ beforeAction,请求前拦截操作
```php
class TestApi extends OutApiAbstract
{
	  protected $defaultConfig = [  
	  // 域名配置
	  'baseUrl' => 'https://postman-echo.com',
	  // 签名配置(模拟)
	  'secret'=>'123455',
	  // 路由配置(下面说明)
	  'routes'=>[
		   // 第一个接口
		  'firstApi'=>[
			  'route'=>'/index/first-api',
			  'method'=>'GET',
		  ],
	  ],
	 // ... 
	 // 此处也可以写一些业务参数，比如签名配置(beforeAction()可做签名操作,见下面说明 )
  ];
	// 使用 (new TestApi($config))->firstApi();
	public function firstApi($params=[])
	{
		// 无参数路由
		return $this->request('firstApi');
	}
	function beforeAction(Request $request, &$options = [])  
	{
	   // TODO: Implement beforeAction() method. 
		// 进行加密签名操作  
		$token=$this->getToken();
	    $options['headers']=array_merge($options['headers']??[],['token'=>$token]);  
		// 此处不止签名,你可以做更多的操作(比如代理)
		// 可参考 https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html  
	 
	}
	private function getToken()
	{
		// 获取当前配置
		$config=$this->getConfig();
	    // 模拟操作,实际业务根据自己逻辑
	    return 	md5($config['baseUrl'].$config['secret']);
    }
}
```
+ isRetry  重试逻辑
```php
function isRetry(?Response $response, $retries = 0): bool  
{  
  // 此处的逻辑前提是配置中开启了重试（参考配置说明）
  // 此处只是做是否重试逻辑
  // 此处你可做路由的筛选,比如某些路由不重试
  $result=$response->getBody();  
  $result=json_decode($result,true);  
 if(!$result || !isset($result['code'])||$result['code']!=200){  
    return  true;  
  }  
  return false;  
  // TODO: Implement isRetry() method.  
}
```

## License  
  
MIT