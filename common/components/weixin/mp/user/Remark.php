<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mp\user;

use extensions\weixin\core\Driver;
use extensions\weixin\core\AccessToken;
use extensions\weixin\core\Exception;

/**
 * 备注助手
 * @author abei<abei@nai8.me>
 * @link https://nai8.me/lang-7.html
 * @package extensions\weixin\mp\user
 */
class Remark extends Driver {

    const API_UPDATE_REMARK_URL = "https://api.weixin.qq.com/cgi-bin/user/info/updateremark";

    /**
     * @var bool 接口令牌
     */
    private $accessToken = false;

    public function init(){
        parent::init();

        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient,'extra'=>[]]))->getToken();
    }

    /**
     * 给一个用户打备注
     *
     * @param $openId
     * @param $remark
     * @return bool
     * @throws Exception
     */
    public function update($openId,$remark){
        $this->httpClient->formatters = ['uncodeJson'=>'extensions\weixin\helpers\JsonFormatter'];
        $response = $this->post(self::API_UPDATE_REMARK_URL."?access_token={$this->accessToken}",['openid'=>$openId,'remark'=>$remark])
            ->setFormat('uncodeJson')->send();

        $data = $response->getData();
        if(isset($data['errcode']) && $data['errcode'] == 0){
            return true;
        }else{
            $this->ResetAccessToken($data);
            throw new Exception($data['errmsg'],$data['errcode']);
        }
    }

}