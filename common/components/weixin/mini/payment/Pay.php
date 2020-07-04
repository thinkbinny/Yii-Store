<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mini\payment;

use extensions\weixin\core\Driver;
use Yii;
use yii\base\Response;
use extensions\weixin\core\Exception;
use yii\httpclient\Client;
use extensions\weixin\helpers\Util;
use extensions\weixin\helpers\Xml;

/**
 * Class Pay
 * 小程序支付功能
 * @package extensions\weixin\mini\payment
 * @Author 七秒记忆 <274397981@qq.com>
 * @Date 2019/3/20 23:18
 */
class Pay extends Driver {

    /**
     * 预付订单街口地址
     */
    const PREPARE_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * Prepare
     * @var
     */
    private $prepare;

    /**
     * 生成预备订单
     * @param array $attrs
     * @return object
     * @throws Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/20 23:18
     */
    protected function prepare($attrs = []){

        if(empty($attrs['out_trade_no'])){
            throw new Exception('缺少统一支付接口必填参数out_trade_no！');
        }elseif (empty($attrs['body'])){
            throw new Exception('缺少统一支付接口必填参数body！');
        }elseif (empty($attrs['total_fee'])){
            throw new Exception('缺少统一支付接口必填参数total_fee！');
        }elseif (empty($attrs['trade_type'])){
            throw new Exception('缺少统一支付接口必填参数trade_type！');
        }elseif (empty($attrs['openid'])){
            throw new Exception('统一支付接口中，缺少必填参数openid！');
        }

        if(empty($attrs['notify_url'])){
            throw new Exception('异步通知地址不能为空');
        }

        $attrs['appid'] = $this->conf['app_id'];
        $attrs['mch_id'] = $this->conf['payment']['mch_id'];
        $attrs['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $attrs['nonce_str'] = Yii::$app->security->generateRandomString(32);
        $attrs['sign'] = Util::makeSign($attrs,$this->conf['payment']['key']);

        $response = $this->post(self::PREPARE_URL,$attrs)
            ->setFormat(Client::FORMAT_XML)
            ->send();

        if($response->isOk == false){
            throw new Exception(self::ERROR_NO_RESPONSE);
        }
        $response->setFormat(Client::FORMAT_XML);

        return $this->prepare = $response->getData();
    }

    /**
     * jsapi类型的支付
     * @param array $attributes
     * @return object prepare
     */
    public function jsApi($attributes = []){
        $attributes['trade_type'] = 'JSAPI';
        $result = $this->prepare($attributes);
        return $result;
    }

    /**
     * 生成一个jsapi类型的配置
     * @param $prepayId
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/20 22:53
     *
     */
    public function configForPayment($prepayId){
        $params = [
            'appId' => $this->conf['app_id'],
            'timeStamp' => strval(time()),
            'nonceStr' => uniqid(),
            'package' => "prepay_id=$prepayId",
            'signType' => 'MD5',
        ];

        $params['paySign'] = Util::makeSign($params,$this->conf['payment']['key']);

        return $params;
    }

    /**
     * 服务器 通知地址 返回数据
     * @param callable $callback
     * @return string
     * @throws Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/20 22:53
     */
    public function handleNotify(callable $callback){

        $notify = $this->getNotify();
        if (!$notify->checkSign()) {
            throw new Exception('签名错误');
        }
        $notify = $notify->getData();
        $isSuccess = $notify['result_code'] === 'SUCCESS';
        $handleResult = call_user_func_array($callback, [$notify, $isSuccess]);

        if (is_bool($handleResult) && $handleResult) {
            $response = [
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK',
            ];
        } else {
            $response = [
                'return_code' => 'FAIL',
                'return_msg' => $handleResult,
            ];
        }

        return Xml::build($response);
    }

    public function getNotify(){
        return new Notify($this->conf['payment']);
    }
}