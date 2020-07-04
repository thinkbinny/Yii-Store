<?php

/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mp\kf;

use yii\httpclient\Client;
use extensions\weixin\core\Driver;
use extensions\weixin\core\AccessToken;
use extensions\weixin\core\Exception;

/**
 * CustomService
 * 发送客服消息接口
 *
 * @author abei<abei@nai8.me>
 * @link https://nai8.me/yii2weixin
 * @package extensions\weixin\mp\kf
 */
class CustomService extends Driver {

    const API_SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send';

    private $accessToken;

    public function init(){
        parent::init();
        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient]))->getToken();
    }

    /**
     * 给某个用户发送某个类型的消息
     *
     * @param $openId string 用户openId
     * @param $type string 消息类型
     * @param $data array 消息内容数组
     * @param $extra array 额外配置
     * @throws Exception
     * @return boolean
     */
    public function send($openId,$type,$data,$extra = []){

        $params = array_merge(['touser'=>$openId,'msgtype'=>$type],[$type=>$data],$extra);

        $this->httpClient->formatters = ['uncodeJson'=>'extensions\weixin\helpers\JsonFormatter'];
        $response = $this->post(self::API_SEND_URL."?access_token={$this->accessToken}",$params)->setFormat('uncodeJson')->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $response->setFormat(Client::FORMAT_JSON);
        $data = $response->getData();

        if(isset($data['errcode']) && $data['errcode'] != 0){
            $this->ResetAccessToken($data);
            throw new Exception($data['errmsg'], $data['errcode']);
        }

        return true;
    }
}