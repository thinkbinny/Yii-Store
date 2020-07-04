<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mp\qrcode;

use extensions\weixin\core\Driver;
use extensions\weixin\core\AccessToken;
use yii\httpclient\Client;

/**
 * Qrcode
 * 二维码生成接口
 * @package extensions\weixin\mp\qrcode
 * @link https://nai8.me/yii2weixin
 * @author abei<abei@nai8.me>
 */
class Qrcode extends Driver {

    private $accessToken;

    private $ticket;
    //  生成临时二维码
    const API_QRCODE_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create';

    // 通过ticket换取二维码
    const API_TICKET_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';

    public function init()
    {
        parent::init();
        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient]))->getToken();
    }

    /**
     * 生成临时二维码
     *
     * @param $seconds integer 过期秒数
     * @param $val integer 二维码的值
     * @author abei<abei@nai8.me>
     * @return array
     */
    public function intTemp($val,$seconds = 2592000){
        $this->ticket = $this->temp($val,$seconds);
        return $this->ticket;
    }

    /**
     * @param $val static 字符串
     * @param int $seconds
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/25 8:10
     */
    public function strTemp($val,$seconds = 2592000){
        $this->ticket = $this->temp($val,$seconds);
        return $this->ticket;
    }

    /**
     * 生成一个临时二维码
     * 此方法会根据$val的类型来决定是字符串还是整数。
     *
     * @param int $seconds  过期时间
     * @param $val 值
     * @since 1.2
     */
    public function temp($val,$seconds = 2592000){
        if(is_int($val) && $val > 0){
            return $this->tempQrcode('QR_SCENE',$seconds,['scene_id'=>$val]);
        }else{
            return $this->tempQrcode('QR_STR_SCENE',$seconds,['scene_str'=>$val]);
        }
    }

    /**
     * 生成临时二维码
     * @param string $action    数字还是字符串类型
     * @param int $seconds  二维码过期时间
     * @param array $scene  值
     * @return mixed
     */
    private function tempQrcode($action = 'QR_SCENE', $seconds = 2592000, $scene = ['scene_id'=>0]){
        $params = array_merge(['expire_seconds'=>$seconds,'action_name'=>$action,'action_info'=>['scene'=>$scene]]);
        $response = $this->post(Qrcode::API_QRCODE_URL."?access_token={$this->accessToken}",$params)
            ->setFormat(Client::FORMAT_JSON)->send();

        $response->setFormat(Client::FORMAT_JSON);
        return $response->getData();
    }

    /**
     * 生成永久的数字型二维码
     * @param $val integer
     * @return mixed
     */
    public function intForver($val){
        return $this->foreverQrcode('QR_LIMIT_SCENE',['scene_id'=>$val]);
    }

    /**
     * 生成永久的字符型二维码
     * @param $val
     * @return mixed
     */
    public function strForver($val){
        return $this->foreverQrcode('QR_LIMIT_STR_SCENE',['scene_str'=>$val]);
    }

    /**
     * 生成一个永久二维码
     * 此方法会根据$val的类型来决定是字符串还是整数。
     *
     * @param $val 值
     * @since 1.2
     */
    public function forever($val){
        if(is_int($val) && $val > 0){
            return $this->foreverQrcode('QR_LIMIT_SCENE',['scene_id'=>$val]);
        }else{
            return $this->foreverQrcode('QR_LIMIT_STR_SCENE',['scene_str'=>$val]);
        }
    }

    private function foreverQrcode($action = 'QR_LIMIT_SCENE', $scene = ['scene_id'=>0]){
        $params = array_merge(['action_name'=>$action,'action_info'=>['scene'=>$scene]]);
        $response = $this->post(Qrcode::API_QRCODE_URL."?access_token={$this->accessToken}",$params)
            ->setFormat(Client::FORMAT_JSON)->send();

        $response->setFormat(Client::FORMAT_JSON);
        return $response->getData();
    }

    //ticket换取二维码
    public function getQrcode(){
        if(!isset($this->ticket['ticket'])) return '';

        return Qrcode::API_TICKET_URL."?ticket=".$this->ticket['ticket'];
    }

    public function getQrcodeUrl($ticket){
        return Qrcode::API_TICKET_URL."?ticket=".$ticket['ticket'];
    }

}