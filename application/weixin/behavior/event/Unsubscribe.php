<?php
namespace weixin\behavior\event;
use Yii;
use weixin\models\WxUser;

/**
 * Class Unsubscribe 取消关注
 * @package weixin\action
 * @Author 七秒记忆 <274397981@qq.com>
 * @Date 2019/4/1 10:42
 */
class Unsubscribe {
 public static function run($message){
      WxUser::subscribe($message['FromUserName'],0);
      //return
 }

}
