<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;


class MemberAddress extends \yii\db\ActiveRecord{
    const CACHE_KEY_FIND_VIEW_MODEL = 'cache_key_member_address_find_view_model';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_address}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid','province_id','city_id','district_id','is_default','created_at','updated_at'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50],
            [['detail'], 'string', 'max' => 250],
            [['is_default'], 'default', 'value' =>0],
            [['name','phone','detail'], 'default', 'value' =>''],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '地址ID',
            'uid'               => '用户UID',
            'name'              => '收货人姓名',
            'phone'             => '收货人号码',
            'province_id'       => '省份',//如果是空为本地
            'city_id'           => '市区',
            'district_id'       => '县区',
            'address'           => '详细地址',
            'is_default'        => '是否默认',
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
     * @param bool $insert
     * @param array $changedAttributes
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/9 22:33
     */
    public function afterSave($insert, $changedAttributes)
    {
        if(!$this->isNewRecord){
            $cache = self::CACHE_KEY_FIND_VIEW_MODEL.$this->id;
            Yii::$app->cache->delete($cache);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    /**
     * @param $id
     * @return array|mixed|null|\yii\db\ActiveRecord
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/9 22:32
     */
    public static function findViewModel($id){
        $cache = self::CACHE_KEY_FIND_VIEW_MODEL.$id;
        $data  = Yii::$app->cache->get($cache);
        if($data === false){
            $data = self::find()
                ->where("id=:id")
                ->addParams([':id'=>$id])
                ->select("id,uid,name,phone,province_id,city_id,district_id,detail")
                ->asArray()
                ->one();
            if(empty($data)){
                return null;
            }
            Yii::$app->cache->set($cache,$data);
        }
        return $data;
    }

}