<?php
namespace common\models;

use Yii;



class AppsMenu extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%apps_menu}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid','status', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'pid'           => '属于栏目',
            'name'          => '栏目名称',
            'desc'          => '栏目描述',
            'sort'          => '顺序',
            'status'        => '状态',
        ];
    }
}
