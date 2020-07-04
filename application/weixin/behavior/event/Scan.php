<?php
namespace weixin\behavior\event;
use Yii;
use weixin\models\WxUser;
use weixin\models\WxUserTemp;
use weixin\models\User;
/**
 * Class Unsubscribe 推荐二维码参数
 * @package weixin\action
 * @Author 七秒记忆 <274397981@qq.com>
 * @Date 2019/4/1 10:42
 */
class  Scan{
 public static function run($message){
     $rst = 0;
     if($message['EventKey']==null || empty($message['EventKey']) || $message['EventKey']==false){
         $rst =  User::wxUserServer($message['FromUserName'],0);
     }else{
         $uid = ltrim($message['EventKey'],'signup_');//推广者信息或推广场景
         if(!empty($uid)){
             $rst = User::wxUserServer($message['FromUserName'],$uid);
         }
     }
     //WxUser::subscribe($message['FromUserName'],1);
     if(empty($rst)){
         return null;
     }else{
         return $rst;
     }
     //return null;
 }

}
