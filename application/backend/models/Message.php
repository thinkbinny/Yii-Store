<?php
namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%Links}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string  $title
 * @property string  $url
 * @property string  $remark
 * @property integer $status
 * @property integer $sort
 */
class Message extends \common\models\Message
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content'],'trim'],
            [['type','send_type','title','content'], 'required'],
            [['uid','type','send_type','status', 'created_at','updated_at'], 'integer'],
            //[['send_time'],'match','pattern'=>'/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/','message'=>'时间格式不正确'],
            [['title'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 2000],
            ['uid', 'default', 'value' =>0],
            ['status', 'default', 'value' =>0],
            ['type', 'default', 'value' =>1],
            //['send_time','getSendTime']
        ];
    }
    public function afterValidate(){

        if($this->isNewRecord){
            $this->send_time    = $this->getSendTime();
        }
        return true;

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
     * 发送
     */
    public function getSendTime(){

        if(empty($this->send_time)){
           $this->send_time = time();
        }else{
            $this->send_time = strtotime($this->send_time);
        }
       return $this->send_time;
    }


}
