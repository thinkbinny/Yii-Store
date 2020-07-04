<?php
namespace backend\models;

use Yii;

use common\models\DeliveryRule as common;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


class DeliveryRule extends common
{

    public function rules()
    {
        return [
            [['delivery_id','created_at','updated_at'], 'integer'],
            ['region', 'safe'],
            [['first','first_fee','additional','additional_fee'], 'double'],
        ];
    }

    public static function setRuleData($delivery_id,$newData = array()){
        $oldData = self::getOldRuleList($delivery_id);
        if(empty($oldData)){
            self::addRule($delivery_id,$newData);
        }else{
            self::updateRule($delivery_id,$newData,$oldData);
        }
    }
    /**
     * @param $goods_id
     * @param $newData
     * @throws \Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 20:02
     */
    public static function addRule($delivery_id,$newData){
        $model = new self();
        foreach ($newData as $attributes){
            $_model = clone $model;
            $region = [
                'province'  => explode(',',$attributes['province']),
                'citys'     => explode(',',$attributes['citys']),
            ];
            $_model->delivery_id    = $delivery_id;
            $_model->region         = Json::encode($region);
            $_model->first          = $attributes['first'];
            $_model->first_fee      = $attributes['first_fee'];
            $_model->additional     = $attributes['additional'];
            $_model->additional_fee = $attributes['additional_fee'];
            if(!$_model->save()){
                throw new \Exception('保存规格属性出错！');
            }
        }
    }
    /**
     * @param $goods_id
     * @param $data
     * @param $oldData
     * @throws \Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 20:02
     */
    public static function updateRule($delivery_id,$newData,$oldData){
        $i=0;
        foreach ($oldData as $id=> $sku_attr_id){
            if(isset($newData[$i])){
                $model    = self::findOne($id);

                $region = [
                    'province'  => explode(',',$newData[$i]['province']),
                    'citys'     => explode(',',$newData[$i]['citys']),
                ];
                $model->region          = Json::encode($region);
                $model->first           = $newData[$i]['first'];
                $model->first_fee       = $newData[$i]['first_fee'];
                $model->additional      = $newData[$i]['additional'];
                $model->additional_fee  = $newData[$i]['additional_fee'];
                if(!$model->save()){
                    throw new \Exception('保存规格属性出错！');
                }
                unset($newData[$i]);
            }else{
                $list = self::deleteAll("id=:id",[':id'=>$id]);
                if(!$list){
                    throw new \Exception('保存规格属性出错！');
                }
            }
            $i++;
        }
        if(!empty($newData)){
            self::addRule($delivery_id,$newData);
        }
    }
    /**
     * @param $goods_id
     * @return array|null|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 17:59
     */
    public static function getOldRuleList($delivery_id){
        $model  = new self();
        $data   = $model::find()
            ->where("delivery_id=:delivery_id")
            ->addParams([':delivery_id'=>$delivery_id])
            ->select("id,delivery_id")
            ->asArray()
            ->orderBy(['id'=>SORT_ASC])
            ->all();
        if(empty($data)){
            return null;
        }
        $data = ArrayHelper::map($data,'id','delivery_id');
        return $data;
    }
}
