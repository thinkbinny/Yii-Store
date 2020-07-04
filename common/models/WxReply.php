<?php
namespace common\models;

use Yii;



class WxReply extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_reply}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','msg_type', 'created_at','updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 2000],
            [['media_id'], 'string', 'max' => 64],
            ['status', 'default', 'value' =>1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'name'              => '规则名称',
            'type'              => 'KEY类型',
            'msg_type'          => '回复类型',
            'content'           => '回复内容',
            'media_id'          => '回复素材ID',
            'status'            => '规则状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    //消息类型 1:文本(text)、2:图文(news)、3:图片(image)、4:视频(video)、5:语音(voice)
    public static $msgType = [
        1   =>  '文本（text）',
        2   =>  '图文（news）',
        3   =>  '图片（image）',
        4   =>  '视频（video）',
        //5   =>  '语音（voice）',
    ];

    public function getMsgType() {
        return self::$msgType;
    }
    public static function getMsgTypeText($type){
        return self::$msgType[$type];
    }
}
