<?php
namespace common\models;

use Yii;

class AppsMethod extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%apps_method}}';
    }

    public function rules() {
        return [

            [['method'], 'required'],
            [['apps_menu_id','auth','type','sort','status','created_at','updated_at'], 'integer'],
            [['method'], 'string', 'length' => 50],
            [['description'], 'string', 'length' => 200],
            [['request','result'], 'safe'],

        ];
    }
    public function attributeLabels() {
        return [
            'id'                => 'ID',
            'apps_menu_id'      => '接口栏目',
            'method'            => '方法',
            'auth'              => '是否受权',
            'type'              => '类型',
            'description'       => '介绍',
            'request'           => '请求方法',
            'result'            => '返回方法',
            'sort'              => '顺序',
            'status'            => '状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

}
