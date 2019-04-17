<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:33
 */

namespace App;


use App\Services\UserService;
use GuzzleHttp\Client;

class Sms
{

    private $smsUser;

    private $smsPassword;

    private $smsReceiveMobile;

    private $smsSign;

    public function __construct($siteID)
    {
        //初始化站点配置
        $user = new UserService();

        $config = $user->getSmsInfo($siteID);

        if (empty($config)) {
            throw new \Exception('短信配置无效');
        }

        $this->smsUser = $config['smsUser'];
        $this->smsPassword = $config['smsPassword'];
        $this->smsReceiveMobile = $config['smsReceiveMobile'];
        $this->smsSign = $config['smsSign'];
    }

    /**
     * 未支付短信
     */
    public function noPay()
    {

    }

    /**
     * 支付成功
     */
    public function paySuccess($mobile, $nickname, $orderNo)
    {

        $content = '亲爱的%s,您的订单%s正在快马加鞭向您奔来~【%s】';

        $content = sprintf($content, $nickname, $orderNo, $this->smsSign);

        $this->send($mobile, $content);
    }

    public function send($mobile, $content)
    {
        $data = [
            'UserID' => $this->smsUser,
            'PassWord' => md5($this->smsPassword),
            'action' => 'SendSms',
            'message' => urlencode($content),
            'mobile' => $mobile
        ];

        try {
//            $client = new Client();

            $query = http_build_query($data);

            Log::debug($query);
//            $res = $client->request('GET', 'http://www.72dns.com/smsadmin/Sms_Api.aspx/?' . $query);

//            Log::debug($res->getBody());
        } catch (\GuzzleHttp\Exception\GuzzleException $exception) {
            Log::error($exception->getMessage());
        }


    }

}