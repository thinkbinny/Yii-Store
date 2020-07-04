<?php
namespace backend\models;

use Yii;
use common\models\StoreClerk as common;

class StoreClerk extends common
{

        const STATUS_ACTIVE = 1;
        const STATUS_DELETED = 0;
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realname','mobile'], 'trim'],

            [['uid','shop_id'],'required','message'=>'请选择{attribute}'],
            [['realname','mobile'], 'required'],
            [['id','uid','shop_id','is_delete','status' ,'created_at','updated_at'], 'integer'],
            [['realname','mobile'], 'string', 'max' => 20],
            [['is_delete'], 'default', 'value' =>0],
            [['status'], 'default', 'value' =>1],
            ['shop_id','getUserVerify']
        ];
    }

    /**
     * 审核用户，用户是否在同一家店管理多次
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 17:27
     */
    public function getUserVerify(){
        $model = self::find()
            ->where("is_delete=:is_delete and shop_id=:shop_id and uid=:uid")
            ->addParams([':is_delete'=>0,':shop_id'=>$this->shop_id,':uid'=>$this->uid])
            ->select('id')
            ->one();
        if($model->id && $model->id != $this->id){
           /*if($this->isNewRecord){
                $this->addError('uid','该用户已存在') ;
           }else{*/
               $this->addError('shop_id','该用户已管理此门店') ;
           //}
        }
    }
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 16:59
     */
    public function getShopData(){
        return Store::getFindListModel();
    }

    /**
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 17:00
     */
    public function getShopNameText(){
        $shop_id = $this->shop_id;
        $data = Store::getFindListModel();
        if(isset($data[$shop_id])){
            return $data[$shop_id];
        }else{
            return '';
        }
    }

}
