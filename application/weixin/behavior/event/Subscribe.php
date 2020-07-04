<?php
namespace weixin\behavior\event;
use weixin\models\User;
use weixin\models\WxUserTemp;
use Yii;
use weixin\models\WxReply;
use weixin\models\WxUser;
class Subscribe {
 public static function run($message){

     $rst = 0;
     //普通二维码 或搜索关注
     if($message['EventKey']==null || empty($message['EventKey']) || $message['EventKey']==false){
        $rst =  User::wxUserServer($message['FromUserName'],0);
     }else{
         //推荐关注
         $uid = ltrim($message['EventKey'],'qrscene_signup_');//推广者信息或推广场景
         if(!empty($uid)){
             $rst = User::wxUserServer($message['FromUserName'],$uid);
         }
     }
     //WxUser::subscribe($message['FromUserName'],1);
     if(empty($rst)){
        return WxReply::getReplyType(2);
     }else{
         return $rst;
     }
 }

}
