<?php
namespace backend\models;

use Yii;

use common\models\ReturnAddress as common;




class ReturnAddress extends common
{
    const CACHE_KEY_FIND_DATA_MODEL = 'cache_key_return_address_find_data_model';
    const STATUS_ACTIVE           = 1;
    const STATUS_DELETED          = 0;

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 15:25
     */
    public function rules()
    {
        return [
            [['name','phone','detail','sort'], 'trim'],
            [['name','phone','detail','sort'], 'required'],
            [['id','sort','status','created_at','updated_at'], 'integer'],
            [['name','phone'], 'string', 'max' => 50],
            [['detail'], 'string', 'max' => 250],
            [['sort'], 'default', 'value' =>50],
            [['status'], 'default', 'value' =>1],

        ];
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

    public static function getAddress(){
        $data = Yii::$app->cache->get(self::CACHE_KEY_FIND_DATA_MODEL);
        if($data === false){
            $data = self::find()
                ->where("status=:status")
                ->addParams([':status'=>1])
                ->indexBy('id')
                ->select('id,name,phone,detail')
                ->asArray()
                ->orderBy(['sort'=>SORT_ASC,'id'=>SORT_DESC])
                ->all();
            if(!empty($data)){
                Yii::$app->cache->set(self::CACHE_KEY_FIND_DATA_MODEL,$data);
            }
        }
        return $data;
    }


}
