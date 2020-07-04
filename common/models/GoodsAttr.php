<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class GoodsAttr extends \yii\db\ActiveRecord{
    const CACHE_KEY_FIND_MODEL_TEXT = 'cache_key_goods_attr_find_model_text';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_attr}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_required','type','model_type','sort','status','created_at','updated_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 100],
            [['is_required','status'], 'default', 'value' =>1],
            [['sort'], 'default', 'value' =>50],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '属性ID',
            'name'              => '属性名称',
            'is_required'       => '是否必填',
            'type'              => '属性类型',
            'model_type'        => '模型类型',
            'description'       => '属性描述',
            'sort'              => '属性顺序',
            'status'            => '属性状态',
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
    public static function findModelText($id){
        $id = (int)$id;
        if(empty($id)){
            return '';
        }
        static $list;
        $cache_key = self::CACHE_KEY_FIND_MODEL_TEXT;

        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get($cache_key);
        }
        $key = $id;
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else {
            $model = self::find()
                ->where("id=:id")
                ->addParams([':id' => $id])
                ->select('name')
                ->asArray()
                ->one();

            if (!empty($model)) {
                $name = $list[$key] = $model['name'];
                /* 缓存用户 */
                $max   = 5000;
                $count = count($list);
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set($cache_key,$list,2592000);//保存一个月
            }else{
                $name = '';
            }
        }
        return $name;
    }
}
