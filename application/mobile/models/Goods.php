<?php
namespace mobile\models;

use common\models\Goods as common;
use Yii;


class Goods extends common {

    /**
     * 关联内容表
     * @return \yii\db\ActiveQuery
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/6 23:42
     */
    public function  getContent()
    {
        return $this->hasOne(GoodsDetail::className(), ['goods_id' => 'id']);
    }

    /**
     * 关联图片
     * @return \yii\db\ActiveQuery
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/7 0:01
     */
    public function getImage(){

        return $this->hasMany(GoodsImage::className(), ['goods_id' => 'id']);
    }

    public function getAttr(){
        return $this->hasMany(GoodsAttrRelation::className(), ['goods_id' => 'id']);
    }



}
