<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class MemberPropertyLog extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_property_log}}';
    }
    /**
     * @inheritdoc
    1=>'管理员操作',
    2=>'用户充值',
    3=>'用户消费',
    4=>'订单退款',
    5=>'积分赠送'
     */
    public function rules()
    {
        return [
            [['uid','scene','money_change','money'], 'required'],
            [['uid','scene','created_at'], 'integer'],
            ['scene', 'in', 'range' => [1,2,3,4,5,6]],
            [['remarks'], 'string', 'max' => 200],
            [['money_change','money'], 'default', 'value' =>0],
            ['created_at', 'default', 'value' =>time()],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'uid'           => '用户UID',
            'scene'         => '场景',
            'money_change'  => '变更余额',
            'money'         => '当前余额',
            'remarks'       => '备注',
            'created_at'    => '创建时间',
        ];
    }

    /**
     * 场景类型
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/6 11:00
     */
    public function getScene(){
        return [
          1=>'管理员操作',
          2=>'用户充值',
          3=>'用户消费',
          4=>'订单退款',
          5=>'用户提现',
          6=>'提现反驳',
        ];
    }

    public function getSceneText(){
        $data = $this->getScene();
        return $data[$this->scene];
    }
}
