<?php
namespace mobile\models;

use common\models\Cart as common;
use Yii;


class Cart extends common {

    public function rules()
    {
        return [
            [['uid','sku_id','quantity'], 'trim'],
            [['uid','sku_id','quantity'], 'required'],
            [['id','uid','sku_id','quantity','created_at','updated_at'], 'integer'],
            [['quantity'], 'default', 'value' =>1],
            [['fengxiao_uid'], 'default', 'value' =>0],
        ];
    }

    public function setCart(){
        $model = self::find()
            ->where("uid=:uid and sku_id=:sku_id")
            ->addParams([':uid'=>$this->uid,':sku_id'=>$this->sku_id])
            ->one();
        if(empty($model)){
            $model = new self();
            $model->quantity   = $this->quantity;
            $model->sku_id   = $this->sku_id;
            $model->uid      = $this->uid;
        }else{
            $model->quantity   += $this->quantity;
        }
        $model->fengxiao_uid = $this->fengxiao_uid;
        if($model->save()){
            return true;
        }else{
            //print_r();
            $this->addErrors($model->getErrors());
            return false;
        }
    }


}
