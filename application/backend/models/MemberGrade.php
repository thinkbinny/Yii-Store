<?php
namespace backend\models;
use yii\behaviors\TimestampBehavior;
use common\models\MemberGrade as common;
use Yii;
class MemberGrade extends common
{

    const CACHE_DATA_KEY = 'UserGradeData';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }
    /**
     * @return array
     */
    public function getStatus() {
        return self::$displays;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['name','weight','upgrade','equity'],'required'],
            [['id','weight','status', 'created_at','updated_at'], 'integer'],
            ['name', 'string', 'max' => 20],
            ['weight', 'unique'],
            [['upgrade','equity'], 'double'],

            ['status', 'default', 'value' =>1],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete(self::CACHE_DATA_KEY);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * 获取等级配置
     * @return array|mixed|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/12/21 10:10
     */
    public static function getUserGrade(){
        $data = Yii::$app->cache->get(self::CACHE_DATA_KEY);
        if($data === false){
            $data = MemberGrade::find()
                ->indexBy('id')
                ->asArray()
                ->all();
            Yii::$app->cache->set(self::CACHE_DATA_KEY,$data,8400);
        }
        return $data;
    }

}
