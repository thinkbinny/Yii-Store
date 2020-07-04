<?php
namespace common\models;

use Yii;



class WxTplMsg extends \yii\db\ActiveRecord{

    public static $status = [
      0 => '未启用',
      1 => '已启用',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_tpl_msg}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','created_at','updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 255],
            [['template_id','template_sn'], 'string', 'max' => 64],
            ['status', 'default', 'value' =>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'title'             => '模板名称',
            'template_sn'       => '模板编号',
            'template_id'       => '模板ID',
            'remark'            => '模板演示',
            'status'            => '模板状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    public function getStatus(){
        return self::$status;
    }

    public function getStatusText($status){
        return self::$status[$status];
    }
}
