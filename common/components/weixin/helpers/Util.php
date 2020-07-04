<?php

namespace extensions\weixin\helpers;

use yii\base\Component;

/**
 * 工具类库
 * Class Util
 * @package extensions\weixin\helpers
 * @Author 七秒记忆 <274397981@qq.com>
 * @Date 2019/3/21 0:02
 */
class Util extends Component {

    /**
     * 生成支付签名前相关参数到url的转化
     *
     * @param $params array 相关参数
     * @return string
     */
    static public function paramsToUrl($params){
        $buff = "";
        foreach($params as $k=>$v){
            if($k != "sign" && $v != "" && is_array($v) == false){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff,"&");
        return $buff;
    }

    static public function makeSign($params,$key){
        ksort($params);
        $str = self::paramsToUrl($params);
        $str .= "&key=".$key;
        return strtoupper(md5($str));
    }

}