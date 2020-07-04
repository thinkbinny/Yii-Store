<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class StoreClerk extends \yii\db\ActiveRecord{

    const CACHE_KEY_NAME_LIST = 'cache_key_store_clerk_realname_list';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_clerk}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid','shop_id','is_delete','status' ,'created_at','updated_at'], 'integer'],
            [['realname','mobile'], 'string', 'max' => 20],
            [['shop_id'], 'default', 'value' =>0],
            [['status'], 'default', 'value' =>1],
        ];
    }
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '店员ID',
            'uid'               => '店员用户',
            'shop_id'           => '所属门店',
            'realname'          => '店员姓名',
            'mobile'            => '手机号',
            'phone'             => '联系电话',
            'is_delete'         => '是否删除',//0未删除 1已删除
            'status'            => '状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    public static function getRealnameText($id){

        if(empty($id)){
            return '';
        }

        static $list;
        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get(self::CACHE_KEY_NAME_LIST);
        }
        $key = "s{$id}";
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else {
            $model = self::find()
                ->where("id=:id")
                ->addParams([':id' => $id])
                ->select('realname')
                ->asArray()
                ->one();

            if (!empty($model)) {
                $name = $list[$key] = $model['name'];
                /* 缓存用户 */
                $max   = 10000;
                $count = count($list);
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set(self::CACHE_KEY_NAME_LIST,$list,999999);
            }else{
                $name = '';
            }
        }
        return $name;
    }
}
