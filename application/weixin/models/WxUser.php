<?php
namespace weixin\models;
use common\models\WxUser as Common;
use ext\weixin\Application;
use yii\behaviors\TimestampBehavior;
class WxUser extends Common{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    public static function getWeixinInfo($uid,$openid){
        $app    = new Application();
        $weixin = $app->driver("mp.user");
        $appid  = $weixin->conf['app_id'];
        $info   = $weixin->info($openid);
        $info['appid']  = $appid;
        self::wxUserSave($uid,$info);
    }

    /**
     * 取消关注
     */
    public static function subscribe($openid,$subscribe=0){
        WxUser::updateAll(['subscribe'=>$subscribe,'updated_at'=>time()],'openid=:openid',[':openid'=>$openid]);
    }
}
