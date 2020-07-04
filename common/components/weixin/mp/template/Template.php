<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mp\template;

use extensions\weixin\core\Driver;
use extensions\weixin\core\AccessToken;
use extensions\weixin\core\Exception;
use yii\httpclient\Client;

/**
 * 模板消息助手
 * @package extensions\weixin\mp\template
 * @author abei<abei@nai8.me>
 * @link https://nai8.me/lang-7.html
 */
class Template extends Driver {

    private $accessToken;
    /**
     * 获得模板ID
     */
    const API_ADD_TEMPLATE_URL = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template';
    /**
     * 获取模板列表
     */
    const API_GET_TEMPLATE_LIST_URL = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template';
    /**
     * 发送模板消息
     */
    const API_SEND_TEMPLATE_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send';

    public function init(){
        parent::init();
        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient]))->getToken();
    }
    /**
     * 获得模板ID
     */
    public function getAddTemplate($template_id_short){
        $response = $this->post(self::API_ADD_TEMPLATE_URL."?access_token={$this->accessToken}",[
            'template_id_short'=>$template_id_short
        ])->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $response->setFormat(Client::FORMAT_JSON);
        $data = $response->getData();
        if(isset($data['errcode']) && $data['errcode'] <> 0){
            $this->ResetAccessToken($data);
            throw new Exception($data['errmsg'],$data['errcode']);
        }

        return $data;
    }
    /**
     * 获取模板列表
     */
    public function getTemplate(){
        $response = $this->post(self::API_GET_TEMPLATE_LIST_URL."?access_token={$this->accessToken}")->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $response->setFormat(Client::FORMAT_JSON);
        $data = $response->getData();

        if(isset($data['errcode']) && $data['errcode'] <> 0){
            $this->ResetAccessToken($data);
            throw new Exception($data['errmsg'],$data['errcode']);
        }

        return $data;
    }
    /**
     * 发送一个模板消息
     */
    public function send($openId,$templateId,$url,$data,$miniprogram=array()){
        $formatData = [];
        foreach($data as $key=>$val){
            if(is_string($val)){
                $formatData[$key] = ['value'=>$val,'color'=>'#4D4D4D'];
            }elseif (is_array($val)){
                if(isset($val['value'])){
                    $formatData[$key] = $val;
                }else{
                    $formatData[$key] = ['value'=>$val[0],'color'=>$val[1]];
                }
            }
        }

        $params = [
            'touser'=>$openId,
            'template_id'=>$templateId,
            'url'=>$url,
            'data'=>$formatData
        ];
        if(!empty($miniprogram)){
            $params = array_merge($params,$miniprogram);
        }

        $response = $this->post(self::API_SEND_TEMPLATE_URL."?access_token=".$this->accessToken,$params)
            ->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $data = $response->setFormat(Client::FORMAT_JSON)->getData();

        if(isset($data['errcode']) && $data['errcode'] != 0){
            $this->ResetAccessToken($data);
            throw new Exception($data['errmsg'],$data['errcode']);
        }

        return true;
    }

}