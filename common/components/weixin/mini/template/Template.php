<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mini\template;
use extensions\weixin\core\AccessToken;
use extensions\weixin\core\Driver;
use Yii;
use yii\httpclient\Client;
use extensions\weixin\core\Exception;
/**
 * Template
 * 小程序模板消息
 * @author abei<abei@nai8.me>
 * @link https://nai8.me/yii2weixin
 * @package abei2017\weixin\mini\template
 */
class Template extends Driver {

    const API_SEND_TMPL = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=';//'https://api.weixin.qq.com/cgi-bin/message/weixinopen/template/send?access_token=';
    private $accessToken = null;
    public function init(){
        parent::init();
        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient]))->getToken();
    }
    /**
     * 发送模板消息
     *
     * @param $toUser     //接收者（用户）的 openid
     * @param $templateId //模板消息的id
     * @param $formId     //为 submit 事件带上的 formId；支付场景下，为本次支付的 prepay_id
     * @param $data       //模板内容
     * @param array $extra
     */
    public function send($toUser,$templateId,$formId,$data,$extra = []){
        $params = array_merge([
            'touser'     => $toUser,
            'template_id'=> $templateId,
            'form_id'    => $formId,
            'data'       => $data,
        ],$extra);
        $response = $this->post(self::API_SEND_TMPL.$this->accessToken,$params)->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }
        $response->setFormat(Client::FORMAT_JSON);
        $data = $response->getData();
        if(isset($data['errcode']) && $data['errcode'] <> 0){
            $this->ResetAccessToken($data);
            //throw new Exception($data['errmsg'],$data['errcode']);
        }
        return $data;
    }

    
}