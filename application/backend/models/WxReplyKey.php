<?php
namespace backend\models;

use Yii;
use common\models\WxReplyKey as Common;

/**
 * This is the model class for table "{{%wx_reply}}".

 */
class WxReplyKey extends Common
{
    public static $reply_id;
    /** scenarios
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','wx_reply_id'], 'required'],
            ['name', 'unique'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'name'              => '关键字',
            'wx_reply_id'       => 'ReplyId',
        ];
    }
    public static function string2array($key){
        return preg_split('/\s*,\s*/',trim($key),-1,PREG_SPLIT_NO_EMPTY);
    }

    public static function array2string($key){
        return  implode(',',$key);
    }

    public static function addKey($reply_id,$key)
    {
        if(empty($key)) return ;

        foreach ($key as $name){
            $model = new WxReplyKey();
            $count = $model->find()->where(['name'=>$name])->count();
            if(empty($count)){ //添加
                $model->name        = $name;
                $model->wx_reply_id = $reply_id;
                $model->save();
            }
        }
    }

    public static function removeKey($key){
        if(empty($key)) return ;
        foreach ($key as $name){
            $model = WxReplyKey::find()->where(['name'=>$name])->one();
            if(!empty($model)){
                $model->delete();
            }
        }
    }

    public static function updateKey($reply_id,$oldKey,$newKey){
        if(!empty($oldKey) || !empty($newKey)){
            $oldKeyArray = self::string2array($oldKey);
            $newKeyArray = self::string2array($newKey);
            self::addKey($reply_id,array_values(array_diff($newKeyArray,$oldKeyArray)));
            self::removeKey(array_values(array_diff($oldKeyArray,$newKeyArray)));
        }
    }

    public static function findKey($key,$wx_reply_id=0){
        if(empty($key)) return ['status'=>false,'message'=>'关键字不能为空'];
        $data   = self::string2array($key);
         $model = WxReplyKey::find()
            ->where(['in','name',$data])
            ->select('name')
            ->asArray();
         if(!empty($wx_reply_id)){
             $model->andWhere(['<>','wx_reply_id',$wx_reply_id]);
         }
        $volist = $model->all();

        if(empty($volist)){
            return ['status'=>true,'message'=>'ok'];
        }else{
            $name = '';
            foreach ($volist as $k=> $val){
                if($k>0){
                    $name .= ',';
                }
                    $name .= $val['name'];
            }
            return ['status'=>false,'message'=>'关键字（'.$name.'）已存在。'];
        }
    }

    /**
     * @param $wx_reply_id
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/9/6 14:34
     */
    public static function getFinds($wx_reply_id){
        $volist = WxReplyKey::find()
            ->where(['wx_reply_id'=>$wx_reply_id])
            ->select('name')
            ->asArray()
            ->all();
        $name = '';
        foreach ($volist as $k=>$val){
            if($k>0){
                $name .= ',';
            }
            $name .= $val['name'];
        }
        return $name;
    }
    /**
     * 删除规则
     */
    public static function deleteReply($reply_id){
        WxReplyKey::deleteAll("wx_reply_id=:reply_id",[':reply_id'=>$reply_id]);
    }
}
