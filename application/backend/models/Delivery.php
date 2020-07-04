<?php
namespace backend\models;

use Yii;

use common\models\Delivery as common;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;


class Delivery extends common
{
    const CACHE_KEY_DELIVERY_LIST = 'cache_key_delivery_list';
    const STATUS_ACTIVE           = 1;
    const STATUS_DELETED          = 0;

    public $rule;
    public function rules()
    {
        return [
            [['name','rule'], 'trim'],
            [['name'], 'required'],
            [['mode'], 'required','message'=>'请选择{attribute}'],
            [['sort'], 'required'],
            [['rule'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['mode','status'], 'default', 'value' =>1],
            [['sort'], 'default', 'value' =>50],

        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios(); // TODO: Change the autogenerated stub
        return $scenarios;

    }

    /**
     * @var array
     */
    public static $status = [
        self::STATUS_ACTIVE => '启用',
        self::STATUS_DELETED => '禁止',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/17 11:58
     */
    public function getStatus(){
        return self::$status;
    }

    /**
     * @return mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/17 11:58
     */
    public function getStatusText(){
        if(isset(self::$status[$this->status])){
            return self::$status[$this->status];
        }else{
            return '--';
        }
    }


    /**
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/3 18:03
     */
    public function getRegion(){
        $data = Region::getProvinceAndCityData();
        return Json::encode($data,JSON_UNESCAPED_SLASHES);
    }

    public function getCityCount(){
        $data   = Region::getProvinceAndCityData();
        $count  = 0;
        foreach ($data as $vo){
            $count +=count($vo['city']);
        }
        return $count;
    }
    /**
     * 获取
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/3 18:22
     */
    public function getDeliveryRule(){
        $data  = array();
        if(!$this->isNewRecord){
            $volist = DeliveryRule::find()
                ->where("delivery_id=:delivery_id")
                ->addParams([':delivery_id'=>$this->id])
                ->select('region,first,first_fee,additional,additional_fee')
                ->asArray()
                ->orderBy(['id'=>SORT_ASC])
                ->all();
            foreach ($volist as $array){
                $region = $array['region'];
                $region = Json::decode($region,true);
                unset($array['region']);
                $array  = ArrayHelper::merge($array,$region);
                $data[] = $array;
            }
            //print_r($data);exit;

        }
        return Json::encode($data);
    }


    /**
     * 自定义保存
     */
    public function save($runValidation = true, $attributeNames = null)
    {

        //开启事务
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        if(parent::save($runValidation, $attributeNames)){

            try{
                if($this->isNewRecord){
                    $delivery_id = Yii::$app->db->getLastInsertID();
                }else{
                    $delivery_id = $this->id;
                }
                DeliveryRule::setRuleData($delivery_id,$this->rule);
                $transaction->commit();  // 提交
                return true;
            }catch(\Exception $exception){
                header('content-type:application/'.Response::FORMAT_JSON.';charset=utf-8');
                $message = $exception->getMessage();
                $transaction->rollBack();  // 回滚
                $data = ['status'=>false,'message'=>$message];
                exit(json_encode($data));
            }
        }

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
        Yii::$app->cache->delete(self::CACHE_KEY_DELIVERY_LIST);
    }

    /**
     * @return array|mixed|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/4 14:11
     */
    public static function getFindDataList(){
        $key  = self::CACHE_KEY_DELIVERY_LIST;
        $data = Yii::$app->cache->get($key);
        if($data === false){
        $data = self::find()
            ->where("status=:status")
            ->addParams([':status'=>1])
            ->select("id,name")
            ->asArray()
            ->orderBy(['sort'=>SORT_ASC,'updated_at'=>SORT_DESC])
            ->all();
        $data = ArrayHelper::map($data,'id','name');
        Yii::$app->cache->set($key,$data);
        }
        if(empty($data)){
            return [];
        }
        return $data;
    }
}
