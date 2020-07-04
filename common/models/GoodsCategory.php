<?php
namespace common\models;

use common\components\Func;
use Yii;
use yii\behaviors\TimestampBehavior;


class GoodsCategory extends \yii\db\ActiveRecord{

    const CACHE_KEY_LIST = 'cache_key_goods_category_list';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_category}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','parent_id','image_id','sort','is_delete','status' ,'created_at','updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['sort'], 'default', 'value' =>50],
            [['is_delete'], 'default', 'value' =>0],
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
            'id'                => '分类ID',
            'parent_id'         => '所属分类',
            'name'              => '分类名称',
            'image_id'          => '分类图片',
            'sort'              => '分类顺序',
            'is_delete'         => '是否删除',//0未删除 1已删除
            'status'            => '状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    public static function getCategoryData(){
        $data = Yii::$app->cache->get(self::CACHE_KEY_LIST);
        if($data === false){
            $volist = self::find()
                ->where("status=:status and is_delete=:is_delete")
                ->addParams([':status'=>1,':is_delete'=>0])
                ->select("id,name,image_id")
                ->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])
                ->asArray()
                ->all();
            $data = array();
            foreach ($volist as $vo):
                $image_url = '';
                if(!empty($vo['image_id'])){
                    $image_url = Func::getImageUrl($vo['image_id']);
                }
                $data[$vo['id']]=[
                  'id'          =>  $vo['id'],
                  'name'        =>  $vo['name'],
                  'image_url'   =>  $image_url,
                ];
                Yii::$app->cache->set(self::CACHE_KEY_LIST,$data);
            endforeach;
        }
        return $data;
    }
}
