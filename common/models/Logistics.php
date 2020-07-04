<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class Logistics extends \yii\db\ActiveRecord{
    const CACHE_KEY_LIST          = 'cache_key_Logistics_list';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%logistics}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','sort','status','created_at','updated_at'], 'integer'],
            [['name','code'], 'string', 'max' => 50],
            [['sort'], 'default', 'value' =>50],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '物流ID',
            'name'              => '物流名称',
            'code'              => '物流代码',
            'sort'              => '顺序',
            'status'            => '状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/30 14:18
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * 保存之后执行
     * @param bool $insert
     * @param array $changedAttributes
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/4 14:11
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        Yii::$app->cache->delete(self::CACHE_KEY_LIST);
    }
}