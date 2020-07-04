<?php
namespace weixin\models;
use common\models\Member as Common;


class Member extends Common{
   public $openid;//用户的标识，对当前公众号唯一
   public function wxLogin($openid){
      /* $uid = OauthUser::getLoginUid($openid,'wx');
       if(empty($uid)){
            return ;//没有登陆成功
       }*/


   }


}
