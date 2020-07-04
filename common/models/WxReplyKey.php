<?php
namespace common\models;

use Yii;



class WxReplyKey extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_reply_key}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_reply_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'material_id'       => '回复素材ID',
            'status'            => '规则状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }
}
