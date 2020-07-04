<?php
namespace backend\models;

use Yii;
use common\models\Store as common;
use yii\helpers\ArrayHelper;

class Store extends common
{
        const STORE_NAME_KEY_CACHE   = 'store_name_key_cache';
        const STATUS_ACTIVE = 1;
        const STATUS_DELETED = 0;
        /**
         * @var array
         */
        public static $status = [
            -1 => '不通过',
            0  => '待审核',
            1  => '审核通过',
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
         * @return array
         * @Author 七秒记忆 <274397981@qq.com>
         * @Date 2020/1/17 11:58
         */
        public function getIsCheck(){
            return [
                1=>'支持',
                0=>'不支持',
            ];
        }

        /**
         * @return mixed
         * @Author 七秒记忆 <274397981@qq.com>
         * @Date 2020/1/17 11:58
         */
        public function getIsCheckText(){
            $data = $this->getIsCheck();
            return $data[$this->is_check];
        }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','linkman','phone','shop_hours','province_id','city_id','district_id','address','coordinate','summary','open_account','realname','account'], 'trim'],
            [['name'], 'required'],
            [['logo_image_id','uid'],'required','message'=>'请选择{attribute}'],
            [['name','linkman','phone','shop_hours'], 'required'],
            [['province_id','city_id','district_id',],'required','message'=>'请选择{attribute}'],
            [['address','coordinate'], 'required'],
            [['tixian_type','open_account','realname','account'], 'required'],
            [['id','parent_uid','uid','logo_image_id','province_id','city_id','district_id','tixian_type','sort','is_check','is_delete','status' ,'created_at','updated_at'], 'integer'],
            [['linkman','phone'], 'string', 'max' => 20],
            ['coordinate', 'string', 'max' => 30],
            [['name','shop_hours','open_account','realname','account'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [['summary'], 'string', 'max' => 250],
            [['sort'], 'default', 'value' =>50],
            [['is_check','is_delete'], 'default', 'value' =>0],
            [['status'], 'default', 'value' =>1],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        Yii::$app->cache->delete(self::STORE_NAME_KEY_CACHE);
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 16:56
     */
    public static function getFindListModel(){
        $data = Yii::$app->cache->get(self::STORE_NAME_KEY_CACHE);
        if($data===false){
            $data = self::find()
            ->where("is_delete = :is_delete")
            ->addParams([':is_delete'=>0])
            ->select('id,name')
            ->asArray()
            ->orderBy(['id'=>SORT_ASC])
            ->all();
            $data = ArrayHelper::map($data,'id', 'name');
            Yii::$app->cache->set(self::STORE_NAME_KEY_CACHE,$data);
        }
        return $data;
    }

}