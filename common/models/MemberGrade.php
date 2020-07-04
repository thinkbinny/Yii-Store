<?php
namespace common\models;

use Yii;



class MemberGrade extends \yii\db\ActiveRecord{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    public static $displays = [
        self::STATUS_ACTIVE => '启用',
        self::STATUS_DELETED => '禁止',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_grade}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','weight','status', 'created_at','updated_at'], 'integer'],
            ['name', 'string', 'max' => 60],
            ['weight', 'unique'],
            [['upgrade','equity'], 'double'],
            ['status', 'default', 'value' =>1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => '等级ID',
            'name'          => '等级名称',
            'weight'        => '等级权重',
            'upgrade'       => '升级条件',
            'equity'        => '等级权益',
            'status'        => '等级状态',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
        ];
    }
}
