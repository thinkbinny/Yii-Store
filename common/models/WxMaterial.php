<?php
namespace common\models;

use Yii;



class WxMaterial extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_material}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_type','created_at','updated_at'], 'integer'],
            [['media_id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 32],
            [['media_id'], 'string', 'max' => 64],
            ['content', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'msg_type'          => '素材类型',
            'name'              => '素材名称',
            'content'           => 'JSON内容',
            'media_id'          => '素材ID',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    //消息类型 1:文本(text)、2:图文(news)、3:图片(image)、4:视频(video)、5:语音(voice)
    public static $msgType = [
        //1   =>  '文本（text）',
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
