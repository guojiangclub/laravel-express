## ibrand/laravel-express:快递信息查询

特点

1. 一套写法兼容所有平台
1. 简单配置即可灵活增减服务商
1. 统一的返回值格式，便于日志与监控

## TODO:

支持更多快递信息查询平台

## 目前平台支持

- [快递100](http://www.kuaidi100.com/)

## 安装

```shell
composer require "ibrand/laravel-express"
```

低于 Laravel5.5 版本:

`config/app.php`文件`providers`数组中添加：

```
iBrand\Express\ServiceProvider::class
```

`config/app.php` 文件`aliases`数组总 添加：

```
'Express'=> iBrand\Express\Facade::class
```

## 使用

实现了查询快递信息路由，支持`web`和`api`,可以自定义prefix：

```php
'route' => [
	'prefix' => 'express',
	'middleware' => ['web'],
],
//or
'route' => [
	'prefix' => 'express',
	'middleware' => ['api'],
],
```

GET请求`http://your.domain/express/query`

参数：no 快递单号

返回结果：

```json
{
    "kuaidi100": {
        "gateway": "kuaidi100",
        "status": "success",
        "result": [
            {
                "time": "2019-01-09 14:16:41",
                "ftime": "2019-01-09 14:16:41",
                "context": "[长沙市]已签收,感谢使用顺丰,期待再次为您服务"
            },
            {
                "time": "2019-01-09 09:03:41",
                "ftime": "2019-01-09 09:03:41",
                "context": "[长沙市]收方客户要求自取快件,待自取"
            },
            {
                "time": "2019-01-09 08:44:17",
                "ftime": "2019-01-09 08:44:17",
                "context": "[长沙市]快件交给周逢平，正在派送途中（联系电话：13017295338）"
            },
            {
                "time": "2019-01-08 16:00:50",
                "ftime": "2019-01-08 16:00:50",
                "context": "[长沙市]快件到达 【长沙浏阳东沙新村营业点】"
            },
            {
                "time": "2019-01-08 14:28:28",
                "ftime": "2019-01-08 14:28:28",
                "context": "[长沙市]快件已发车"
            },
            {
                "time": "2019-01-08 14:17:02",
                "ftime": "2019-01-08 14:17:02",
                "context": "[长沙市]快件在【长沙星沙集散中心】已装车,准备发往 【长沙浏阳东沙新村营业点】"
            },
            {
                "time": "2019-01-08 13:57:56",
                "ftime": "2019-01-08 13:57:56",
                "context": "[长沙市]快件到达 【长沙星沙集散中心】"
            },
            {
                "time": "2019-01-08 12:51:04",
                "ftime": "2019-01-08 12:51:04",
                "context": "[长沙市]快件已发车"
            },
            {
                "time": "2019-01-08 11:46:44",
                "ftime": "2019-01-08 11:46:44",
                "context": "[长沙市]快件在【长沙岳麓银盆岭营业点】已装车,准备发往下一站"
            },
            {
                "time": "2019-01-08 11:00:39",
                "ftime": "2019-01-08 11:00:39",
                "context": "[长沙市]顺丰速运 已收取快件"
            }
        ]
    }
}
```

你也可以通过使用`Facade`来查询快递信息

```php
use Express;

Express::query(request('no'));
```

默认使用 `default` 中的设置来发送，如果你想要覆盖默认的设置。在 `query` 方法中使用第二个参数即可：

```php
use Express;

Express::query((request('no'), ['aliyun']); // 这里的网关配置将会覆盖全局默认，需要在配置文件中配置好相关项
```

也可以这样：

```php
use Express;

Express::query((request('no'), ['aliyun'=>[
    'app_key' => '',
    'app_secret' => '',
    'app_code' => '',
]]);
```

## 查询网关

默认使用 `default` 中的设置来发送，如果你想要覆盖默认的设置。在 `query` 方法中使用第二个参数即可：

```php
use Express;

$number = '810597623758';

Express::query($number, ['kuaidi100']); // 这里的网关配置将会覆盖全局默认值
```

## 返回值

由于使用多网关发送，所以返回值为一个数组，结构如下：
```php
[
    'kuaidi100' => [
        'gateway' => 'kuaidi100',
        'status' => 'success',
        'result' => [...] // 平台返回值
    ],
    'aliyun' => [
        'gateway' => 'aliyun',
        'status' => 'failure',
        'exception' => \Exception 对象
    ],
    //...
]
```

如果所选网关列表均发送失败时，将会抛出 `\Exception` 异常，你可以使用 `$e->results` 获取发送结果。

## 各平台配置说明

### [快递100](http://www.kuaidi100.com/)

```php
    'kuaidi100' => [
        'key' => '',
        'customer' => '',
        'secret' => '',
        'sms_user_id' => ''
    ],
```
