<?php
namespace backend\models;

use Yii;

use common\models\GoodsAttr as common;


class GoodsAttr extends common
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    public function rules()
    {
        return [
            [['name','description'], 'trim'],
            [['name'], 'required'],
            [['is_required','model_type','type'], 'required','message'=>'请选择{attribute}'],
            [['sort'], 'required'],
            [['is_required','type','model_type','sort','status','created_at','updated_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 100],
            [['is_required','model_type','type','status'], 'default', 'value' =>1],
            [['sort'], 'default', 'value' =>50],
            ['is_required','verifyIsRequired'],
            ['type','verifyType']
        ];
    }

    /**
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/2 14:20
     */
    public function verifyIsRequired($attribute){
        if($this->model_type == 1){
            if($this->$attribute == 0){
                $this->addError($attribute,'选择【销售参数】必须选择【是】');
            }
        }
    }
    public function verifyType($attribute){
        if($this->model_type == 1){
            if($this->$attribute != 3){
                $this->addError($attribute,'选择【销售参数】必须选择【多选项】');
            }
        }
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
     * @var array
     */
    public static $model_type = [
        1=>'销售参数',
        2=>'属性参数',
        3=>'规格参数',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getModelType(){
        return self::$model_type;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getModelTypeText(){
        return self::$model_type[$this->model_type];
    }

    /**
     * @var array
     */
    public static $is_required = [
         1 =>  '是',
         0 =>  '否',
    ];
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getIsRequired(){
        return self::$is_required;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getIsRequiredText(){
        return self::$is_required[$this->is_required];
    }

    public static $type = [
      1 => '文本框',
      2 => '单选项',
      3 => '多选项',
    ];
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getType(){
        return self::$type;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/29 22:04
     */
    public function getTypeText(){
        return self::$type[$this->type];
    }
}
