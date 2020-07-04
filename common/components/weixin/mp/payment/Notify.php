<?php
/*
 * This file is part of the ext/yii2-weixin
 *
 * (c) abei <abei@nai8.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace extensions\weixin\mp\payment;

use yii\base\Component;
use extensions\weixin\helpers\Xml;
use extensions\weixin\helpers\Util;
use extensions\weixin\core\Exception;

/**
 * Notify
 * 微信支付通知类
 *
 * @author abei<abei@nai8.me>
 * @link http://nai8.me/yii2weixin
 * @package extensions\weixin\mp\payment
 */
class Notify extends Component {

    /**
     * 收到的通知（数组形式）
     * @var
     */
    protected $notify;

    public $merchant;

    protected $data = false;

    public function getData(){
        if($this->data){
            return $this->data;
        }

        return $this->data = Xml::parse(file_get_contents('php://input'));
    }

    public function checkSign(){
        if($this->data == false){
            $this->getData();
        }

        $sign = Util::makeSign($this->data,$this->merchant['key']);
        if($sign != $this->data['sign']){
            throw new Exception("签名错误！");
        }

        return true;
    }
}