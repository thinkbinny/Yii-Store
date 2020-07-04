<?php
namespace common\models;

use Yii;



class MessageRead extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_read}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','mid','status','updated_at'], 'integer'],
            ['status', 'default', 'value' =>1],
            ['updated_at', 'default', 'value' =>time()],
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
            'mid'           => '消息ID',
            'status'        => '状态',
            'updated_at'    => '阅读时间',
        ];
    }
}
