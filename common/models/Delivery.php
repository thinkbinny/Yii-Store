<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class Delivery extends \yii\db\ActiveRecord{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%delivery}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mode','sort','status','created_at','updated_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['mode','status'], 'default', 'value' =>1],
            [['sort'], 'default', 'value' =>50],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '模板ID',
            'name'              => '模板名称',
            'mode'              => '计费方式',
            'sort'              => '模板顺序',
            'status'            => '模板状态',
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
     * @var array
     */
    public static $mode = [
        1=>'按件数',
        2=>'按重量',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getMode(){
        return self::$mode;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getModeText(){
        return self::$mode[$this->mode];
    }
    /**
     * @param $id
     * @param $city_id
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/9 14:35
     */
    public static function getFreight($id,$city_id){
        $cache  = 'cache_key_goods_freight_id'.$id.$city_id;
        $data   = Yii::$app->cache->get($cache);
        if($data !== false){
            return $data;
        }
        $data   = [
            'mode'          =>1,
            'first'         =>1,
            'first_fee'     =>0,
            'additional'    =>1,
            'additional_fee'=>0,
        ];
        $region     = DeliveryRule::findModelOne($id);
        if(empty($region)){
            return $data;
        }
        $freight    = array();
        foreach ($region as $key=> $value){
            $freight =  $value;
            if(isset($value['region']['citys'])){
                $citys = $value['region']['citys'];
                if(in_array($city_id,$citys)){
                    $freight =  $value;
                    break;
                }
            }
        }
        $info = self::find()
            ->where("id=:id")
            ->addParams([':id'=>$id])
            ->select('mode')
            ->asArray()
            ->one();

        if(empty($info)){
            return $data;
        }
        $data = [
            'mode'          => $info['mode'],
            'first'         => $freight['first'],
            'first_fee'     => $freight['first_fee'],
            'additional'    => $freight['additional'],
            'additional_fee'=> $freight['additional_fee'],
        ];
        Yii::$app->cache->set($cache,$data,1800);
        return $data;
    }
}
