<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mini\qrcode;

use extensions\weixin\core\Driver;
use yii\httpclient\Client;
use extensions\weixin\core\AccessToken;
use extensions\weixin\core\Exception;

/**
 * Qrcode
 * 二维码/小程序码
 * @author abei<abei@nai8.me>
 * @link https://nai8.me/yii2weixin
 * @package abei2017\weixin\mini\qrcode
 */
class Qrcode extends Driver {

    //  获取不受限制的小程序码
    //获取小程序码，适用于需要的码数量极多的业务场景 https://api.weixin.qq.com/wxa/getwxacodeunlimit
    const API_UN_LIMIT_CREATE = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit';//旧 'https://api.weixin.qq.com/weixina/getweixinacodeunlimit';

    //  生成永久小程序码，有数量限制（可定制）
    const API_CREATE = 'https://api.weixin.qq.com/weixina/getweixinacode';

    //  生成永久小程序码，有数量限制（简单类型）
    const API_A_CREATE = 'https://api.weixin.qq.com/cgi-bin/weixinaapp/createweixinaqrcode';

    private $accessToken = null;

    public function init(){
        parent::init();
        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient]))->getToken();
    }
    //生成二维码之后 要用 base64_encode 转换成base64  在输出图片
    //echo "<img width=\"100\" height=\"100\" src=\"data:image/jpg;base64,{$img}\" />";exit;
    /**
     * 生成一个不限制的小程序码
     * @param $scene
     * @param $page string 路径，不能带阐述
     * @param array $extra
     * @throws Exception
     * @return \yii\httpclient\Request;
     */
    public function unLimit($scene,$page,$extra = []){
        $params = array_merge(['scene'=>$scene,'page'=>$page],$extra);
        $response = $this->post(self::API_UN_LIMIT_CREATE."?access_token=".$this->accessToken,$params)
            ->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $contentType = $response->getHeaders()->get('content-type');
        if(strpos($contentType,'json') != false){
            $data = $response->getData();
            /*if(isset($data['errcode'])){
                throw new Exception($data['errmsg'],$data['errcode']);
            }*/
            if(isset($data['errcode']) && $data['errcode'] <> 0){
                $this->ResetAccessToken($data);
                //throw new Exception($data['errmsg'],$data['errcode']);
            }
        }

        return $response->getContent();
    }

    /**
     * 生成永久小程序码
     * 数量有限
     * 该方法生成的小程序码具有更多的可指定性
     */
    public function forever($path,$extra = []){
        $params = array_merge(['path'=>$path],$extra);
        $response = $this->post(self::API_CREATE."?access_token=".$this->accessToken,$params)
            ->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $contentType = $response->getHeaders()->get('content-type');
        if(strpos($contentType,'json') != false){
            $data = $response->getData();
            if(isset($data['errcode'])){
                throw new Exception($data['errmsg'],$data['errcode']);
            }
        }

        return $response->getContent();
    }

    /**
     * 生成永久小程序码
     * 数量有限
     *
     * @param $path string 扫码进入的小程序页面路径
     * @param int $width 二维码的宽度
     * @since 1.2
     */
    public function simpleForever($path, $width = 430){
        $params = ['path'=>$path,'width'=>$width];
        $response = $this->post(self::API_A_CREATE."?access_token=".$this->accessToken,$params)
            ->setFormat(Client::FORMAT_JSON)->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }

        $contentType = $response->getHeaders()->get('content-type');
        if(strpos($contentType,'json') != false){
            $data = $response->getData();
            if(isset($data['errcode'])){
                throw new Exception($data['errmsg'],$data['errcode']);
            }
        }

        return $response->getContent();
    }
}