<?php
/**
 * websocket的客户端
 */

namespace App;

use GuzzleHttp\Client;

class WebSocketClient
{
    private $socket_url;
    private $username;
    private $password;

    public function __construct()
    {
        $this->socket_url = Config::get('socket.url');
        $this->username = Config::get('socket.user');
        $this->password = Config::get('socket.pass');

    }

    /**
     * 小程序消息推送
     * msgNotify
     * @param $client_id
     * @param $content
     */
    public function msgNotify($client_id, $content)
    {
        $event = 'msg-notify';

        $res = $this->sendToClientId($client_id, $event, $content);
        echo 'websocket 消息推送结果';
        var_dump($res);
    }

    /**
     * 后台消息推送
     * @param $client_id
     * @param $content
     */
    public function adminMsgNotify($client_id, $content)
    {
        $event = 'admin-msg-notify';

        return $this->sendToClientId($client_id, $event, $content);
    }

    public function sendToClientId($client_id, $event, $content)
    {

        $client = new Client();

        $data = [
            'act' => 'sendMsg',
            'clientid' => $client_id,
            'msg' => json_encode([
                'event' => $event,
                'data' => $content
            ])
        ];

        return $client->request('POST', $this->socket_url,
            [
                'auth' => [
                    $this->username,
                    $this->password
                ],
                'form_params' => $data
            ]
        );

    }


}