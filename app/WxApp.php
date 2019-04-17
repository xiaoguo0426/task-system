<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 15:43
 */

namespace App;

use App\Services\WxAppService;
use EasyWeChat\Factory;

class WxApp
{
    private $app;

    public function __construct($siteID)
    {
        $wxApp = new WxAppService();

        $miniAppInfo = $wxApp->getMiniAppInfo($siteID);

        if (empty($miniAppInfo)) {
            throw new \Exception('小程序配置无效');
        }

        $this->appid = $miniAppInfo['appid'];

        $this->appSecret = $miniAppInfo['appSecret'];

        $config = [
            'app_id' => $this->appid,
            'secret' => $this->appSecret,

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => LOG_PATH . 'wechat.log',
            ]
        ];

        $this->app = Factory::miniProgram($config);
    }

    public function getTemplateMessageInstance()
    {
        return $this->app->template_message;
    }

    public function getAppCodeInstance()
    {
        return $this->app->app_code;
    }
}