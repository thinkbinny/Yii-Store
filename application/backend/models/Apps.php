<?php
namespace backend\models;
use common\models\Apps as common;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%File}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string  $title
 * @property string  $url
 * @property string  $remark
 * @property integer $status
 * @property integer $sort
 */
class Apps extends common
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    public static $displays = [
        self::STATUS_ACTIVE => '正常',
        self::STATUS_DELETED => '禁止',
    ];
    /**
     * @return array
     */
    public function getStatus() {
        return self::$displays;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['app_name', 'trim'],
            [['app_id','app_secret','app_name'],'required'],
            [['uid','status', 'created_at','updated_at'], 'integer'],
            [['app_id'], 'string', 'max' => 60],
            ['app_id', 'unique'],
            [['app_secret'], 'string', 'max' => 100],
            [['app_name'], 'string', 'max' => 200],
            [['app_desc'], 'string', 'max' => 255],
            ['uid', 'default', 'value' =>0],
            ['status', 'default', 'value' =>1],
        ];
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
    /*public function afterValidate(){

        if($this->isNewRecord){
            $this->app_secret    = $this->ResetSecret();
            $this->app_id        = $this->CreateAppId();
        }
        return true;

    }*/
    public function  ResetSecret(){
        return md5(time());
    }
    public function CreateAppId(){
        $info = self::find()
            ->select('app_id')
            ->orderBy('app_id desc')
            ->one();
        if(empty($info)){
            return '100001';
        }else{
            return $info ->app_id + 1;
        }
    }

}
