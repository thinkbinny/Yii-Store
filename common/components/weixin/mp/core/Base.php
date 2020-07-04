<?php

/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mp\core;

use extensions\weixin\core\AccessToken;
use extensions\weixin\core\Exception;
use extensions\weixin\core\Driver;

/**
 * Base
 * 这里呈现一些基础的内容
 *
 * @package extensions\weixin\core
 * @author abei<abei@nai8.me>
 * @link https://nai8.me/yii2weixin
 */
class Base extends Driver {

    const API_BASE_IP_URL = "https://api.weixin.qq.com/cgi-bin/getcallbackip";

    /**
     * 获取微信服务器IP或IP段
     */
    public function getValidIps(){
        $access = new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient]);
        $accessToken = $access->getToken();

        $response = $this->get(self::API_BASE_IP_URL,['access_token'=>$accessToken])->send();

        $data = $response->getData();
        if(isset($data["ip_list"]) == false){
            $this->ResetAccessToken($result);
            throw new Exception($data['errmsg'],$data['errcode']);
        }

        return $data['ip_list'];
    }
}