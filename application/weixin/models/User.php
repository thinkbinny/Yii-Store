<?php
namespace weixin\models;
use Yii;
use ext\weixin\Application;
use common\models\User as Common;

class User extends Common{

    /**登陆**/
    public static function login($data){
        $openid     = $data['openid'];
        $model = WxUser::find()
            ->where("openid=:openid")
            ->addParams(['openid'=>$openid])
            ->asArray()
            ->one();
        if(empty($model)){
            $id   = WxUser::wxUserSave(0,$data);
            $user = [
                'id' => $id,
                'uid'=> 0,
                'nickname'=>$data['nickname'],
                'headimgurl'=>$data['headimgurl'],
                'sex'=>$data['sex'],
            ];
            //$wx_temp_id = WxUserTemp::wxTemp($data);
        }else{
            $user = $model;
        }
        $user = array_merge($user,['openid'=>$openid]);
        Yii::$app->session->set(Yii::$app->user->idParam,$user);
        return true;
        //return Yii::$app->user->login($user);
    }

    public static function wxUserServer($openid,$t_uid=0){
        $model = new WxUser();
        $info = $model::find()
            ->where("openid=:openid")
            ->addParams([':openid'=>$openid])
            ->select('id,uid')
            ->asArray()
            ->one();
        if(!empty($info)){
            if($info['uid'] == $t_uid && $t_uid !=0){
                return '不能自己推荐给自己';
            }
            return null;
        }
        $app    = new Application();
        $weixin = $app->driver("mp.user");
        $data   = $weixin->info($openid);
        $ret    = WxUser::wxUserSave(0,$data);
        if($ret){ //写出登陆
            if(!empty($t_uid)){
                if($t_uid != 3){
                    WxUserTuiLog::wxUserSignin($t_uid,$data); //不提示 绑定用户才提示
                }
            }
        }
        return 0;
    }
}
