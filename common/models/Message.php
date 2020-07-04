<?php
namespace common\models;

use Yii;



class Message extends \yii\db\ActiveRecord{

    public static $typeList = [
        1 => '系统类型',
    ];
    public static $sendTypeList = [
        0 => '提醒消息',
        1 => '邮件消息',
        2 => '短信消息',
        3 => '全部消息',
    ];

    public static $statusList = [
        -1 => '删除',
        0 => '未发送',
        1 => '已发送',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','type','send_type','status','send_time', 'created_at','updated_at'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 2000],
            ['uid', 'default', 'value' =>0],
            ['type', 'default', 'value' =>1],
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
            'type'          => '消息类型',
            'send_type'     => '发送类型',
            'title'         => '消息标题',
            'content'       => '消息内容',
            'status'        => '状态',
            'send_time'     => '发送时间',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
        ];
    }

    /**
     * @return array
     */
    public function getType(){
        return self::$typeList;
    }
    /**
     * @return array
     */
    public function getSendType() {
        return self::$sendTypeList;
    }
    /**
     * @return array
     */
    public function getStatus() {
        return self::$statusList;
    }
    /**
     * 获取消息类型
     */
    public static function getTypeText($type) {
        return self::$typeList[$type];
    }
    /**
     * 获取状态
     */
    public static function getStatusText($status) {
        return self::$statusList[$status];
    }

    /**
     * 获取发送类型
     */
    public static function getSendTypeText($SendType) {
        return self::$sendTypeList[$SendType];
    }


}
