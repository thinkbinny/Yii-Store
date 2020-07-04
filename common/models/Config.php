<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_config".
 *
 * @property integer $id
 * @property string $name
 * @property string $info
 * @property integer $groupid
 * @property string $value
 */
class Config extends \yii\db\ActiveRecord
{
    const CACHE_KEY_FIND_MODEL = 'cache_key_config_find_model_key';
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyid','title'],'required'],
            ['status','integer'],
            [['data'], 'string'],
            [['keyid'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 100],
            ['keyid', 'unique'],
            ['status','default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'keyid'     => '唯一ID',
            'title'     => '名称',
            'data'      => '数据',
            'status'    => '状态',
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        if(!$this->isNewRecord){
            $keyid   = self::CACHE_KEY_FIND_MODEL . $this->keyid;
            Yii::$app->cache->delete($keyid);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * @param $keyid
     * @return mixed
     */
    public static function getConfigs($keyid) {
        $cache   = self::CACHE_KEY_FIND_MODEL . $keyid;
        $configs = Yii::$app->cache->get($cache);
        if(empty($configs)){
            $configs = self::find()->where(['keyid' => $keyid])->asArray()->one();
            if(empty($configs)){
                return null;
            }
            $configs = json_decode($configs['data'], true);
            Yii::$app->cache->set($cache,$configs);
        }
        return $configs;
    }
}