<?php
namespace common\models;

use Yii;



class Apps extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%apps}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','status', 'created_at','updated_at'], 'integer'],
            [['app_id'], 'string', 'max' => 60],
            [['app_secret'], 'string', 'max' => 100],
            [['app_name'], 'string', 'max' => 200],
            [['app_desc'], 'string', 'max' => 255],
            ['uid', 'default', 'value' =>1],
            ['status', 'default', 'value' =>1],
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
            'scene'        => '应用ID',
            'remarks'    => '应用密钥',
          
            'created_at'    => '创建时间',
           
        ];
    }
}
