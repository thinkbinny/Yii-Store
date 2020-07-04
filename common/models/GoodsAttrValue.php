<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class GoodsAttrValue extends \yii\db\ActiveRecord{
    const CACHE_KEY_FIND_MODEL_DATA = 'cache_key_goods_attr_value_find_model_text';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_attr_value}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_id','sort','status','created_at','updated_at'], 'integer'],
            [['value'], 'string', 'max' => 20],
            [['status'], 'default', 'value' =>1],
            [['sort'], 'default', 'value' =>50],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '属性值ID',
            'attr_id'           => '属性ID',
            'value'             => '属性值',
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
     * 获取属性名
     * @param $id
     * @return mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/8 18:24
     */
    public static function findModelData($id){
        $id = (int)$id;
        if(empty($id)){
            return '';
        }
        static $list;
        $cache_key = self::CACHE_KEY_FIND_MODEL_DATA;

        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get($cache_key);
        }
        $key = $id;
        if(isset($list[$key])){ //已缓存，直接使用
            $data = $list[$key];
        } else {
            $model = self::find()
                ->where("id=:id")
                ->addParams([':id' => $id])
                ->select('value,attr_id,id')
                ->asArray()
                ->one();

            if (!empty($model)) {
                $data = $list[$key] = [
                    'name_id'         => $model['attr_id'],
                    'value_id'        => $model['id'],
                    'name'            => GoodsAttr::findModelText($model['attr_id']),
                    'value'           => $model['value'],
                ];
                /* 缓存用户 */
                $max   = 5000;
                $count = count($list);
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set($cache_key,$list,2592000);//保存一个月
            }else{
                $data = '';
            }
        }
        return $data;
    }
}
