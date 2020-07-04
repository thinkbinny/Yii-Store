<?php
namespace backend\models;

use Yii;
use common\models\WxUser as Common;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%wx_reply}}".

 */
class WxUser extends Common
{
    public static $wxType=[
        1=>'公众号',
        2=>'小程序',
    ];
    public static $subscribe = [
        1 => '是',
        2 => '否',
    ];

    public static $sex = [
        0 => '未知',
        1 => '男',
        2 => '女',
    ];


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }
    public function getWxType(){
        return self::$wxType;
    }
    public function getWxTypeText($val){
        if(empty($val)) return '';
        return self::$wxType[$val];
    }
    public function getSubscribe(){
        return self::$subscribe;
    }
    public function getSubscribeText($val){
        if(empty($val)) return '';
        return self::$subscribe[$val];
    }

    public function getSexText($val){
        if(empty($val)) return '';
        return self::$sex[$val];
    }

}
