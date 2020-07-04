<?php
namespace weixin\models;
use common\models\WxReplyKey as Common;

class WxReplyKey extends Common{
    public static function getReplyId($keyword){
        $model  = new WxReplyKey();
        $result = $model->find()
            ->where("name=:name")
            ->addParams([':name'=>$keyword])
            ->select("wx_reply_id")
            ->asArray()
            ->one();
        if(empty($result)){
            return false;
        }
        return $result['wx_reply_id'];
    }
}
