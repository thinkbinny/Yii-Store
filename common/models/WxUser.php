<?php
namespace common\models;
use common\components\Func;
use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * Class WxUser
 * @package common\models
 * @Author thinkbinny<274397981@qq.com>
 * @Date 2019/1/23 14:56
 */
class WxUser extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','openid'],'required'],
            [['uid','subscribe','sex','is_ok', 'created_at','updated_at'], 'integer'],
            [['openid','nickname','country','province','city','unionid'], 'string', 'max' => 50],
            [['headimgurl'], 'string', 'max' => 200],
            [['created_ip'], 'number'],
            [['nickname','country','province','city','unionid','headimgurl'], 'default', 'value' => ''],
            [['is_ok','subscribe','sex'], 'default', 'value' => 0],
            ['created_ip', 'default', 'value' => Func::get_client_ip(1)],
            ['status', 'default', 'value' => 1],
            ['subscribe_time', 'default', 'value' => time()]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'uid'               => '绑定UID',
            'subscribe'         => '是否关注',
            'openid'            => '用户标识',
            'nickname'          => '微信昵称',
            'headimgurl'        => '用户头像',
            'sex'               => '用户性别',
            'country'           => '所在国家',
            'province'          => '所在省份',
            'city'              => '所在城市',
            'unionid'           => 'UnionID机制',
            'is_ok'             => '是否加载',
            'created_ip'        => '所在城市',
            'subscribe_time'    => '关注时间',
            'status'            => '状态',//1正常 0黑名单
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }


    /**
     * @param $uid
     * @param array $data
     * @return bool
     * @Author 七秒记忆<274397981@qq.com>
     * @Date 2019/1/23 19:12
     */
    public static function wxUserSave($uid,$data=array()){
        if(isset($data['unionid'])){
            $unionid = $data['unionid'];
        }else{
            $unionid = '';
        }
        $model  = WxUser::find()
            ->where("openid=:openid")
            ->addParams([':openid'=>$data['openid']])
            ->one();
        if(empty($model)){
            $model  = new WxUser();
        }

        $is_ok = 1;
        if(isset($data['is_ok']) && !empty($data['is_ok'])){
            $is_ok = $data['is_ok'];
        }
        $model->uid             =   $uid;
        $model->unionid         =   $unionid;
        $model->openid          =   $data['openid'];
        $model->is_ok           =   $is_ok;//$data['is_ok'];
        if(isset($data['nickname'])):
            if(isset($data['subscribe'])){
                $model->subscribe       =   $data['subscribe'];
                if($model->subscribe == 1){
                    $model->subscribe_time       =   time();
                }
            }
            $model->nickname        =   $data['nickname'];
            $model->sex             =   $data['sex'];
            $model->city            =   $data['city'];
            $model->country         =   $data['country'];
            $model->province        =   $data['province'];
            $model->headimgurl      =   $data['headimgurl'];
        endif;

        if($model->validate()){
            $model->save();
            return Yii::$app->db->getLastInsertID();
        }else{
            //print_r($model->errors);exit;
            return false;
        }
    }

    /**
     * @param $data
     * @return mixed
     * @Author 七秒记忆<274397981@qq.com>
     * @Date 2019/1/23 15:33
     */
    protected static function getUserLogin($data,$is_ok=false){
        /**
         * 查看该用户 在微信系列 公众号、小程序、APP登陆等 是否绑定
         */
        $user  = WxUser::find()
            ->where("openid=:openid")
            ->addParams([':openid'=>$data['openid']])
            ->select('id,uid,unionid')
            ->asArray()
            ->one();
        if(!empty($user) && !empty($user['uid'])){
            //如果数据库该（公众号、小程序） 旧unionid 为空时并且 新unionid不能为空时更新
            if(empty($user['unionid']) && (isset($data['unionid']) && !empty($data['unionid']))){
                //WxUser::updateAll(['unionid'=>$data['unionid']],"id=:id",[':id'=>$user['id']]);
                self::wxUserSave($user['uid'],$data);
                return $user['uid'];
            }
            if($is_ok==true){
                self::wxUserSave($user['uid'],$data);
            }
            return $user['uid'];
        }
        /**
         * 查看是否有 UnionID机制 如果已有返回用户UID并且注册openid值
         */
        if(isset($data['unionid']) && !empty($data['unionid'])){
            $user = WxUser::find()
                ->where("unionid=:unionid")
                ->addParams([':unionid'=>$data['unionid']])
                ->select("uid")
                ->one();
            if(!empty($user)){
                self::wxUserSave($user['uid'],$data);
                return $user['uid'];
            }
        }
        //还没有用户注册用户
        //没有注册受权注册
        $uid        = User::autoSignup();
        self::wxUserSave($uid,$data);
        return $uid;
    }

    /**
     * @param $openid string 传入微信用户唯一标识
     * @return array|bool|null|\yii\db\ActiveRecord
     * @Author 七秒记忆<274397981@qq.com>
     * @Date 2019/1/23 22:31
     */
    protected static function getUserLoginUid($openid){
       $model = new WxUser();
       $user  = $model->find()
           ->where("openid=:openid")
           ->addParams([':openid'=>$openid])
           ->select("id,uid,openid,unionid")
           ->asArray()
           ->one();
       if(!empty($user)){
           return $user;
       }else{
           return false;
       }
    }

    /**
     *
     */
    public static function getOpenid($uid,$wx_type=2){
        if($wx_type==1){
            $model = new WxUser();
            $user  = $model->find()
                ->where("uid=:uid")
                ->addParams([':uid'=>$uid])
                ->select("openid,subscribe")
                ->asArray()
                ->one();
            if(!empty($user)){
                if($wx_type == 1){
                    if($user['subscribe'] == 0){
                        return false;
                    }
                }
                return $user['openid'];

            }else{
                return false;
            }
        }else{
           //小程序
        }
    }
}
