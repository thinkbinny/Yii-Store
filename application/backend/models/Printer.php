<?php
namespace backend\models;

use Yii;

use common\models\Printer as common;
use yii\helpers\Json;


class Printer extends common
{

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
            [['name','config','amount','sort'], 'trim'],
            [['name','type','config','amount','sort'], 'required'],
            [['id','type','amount','sort','status','created_at','updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['config'], 'string', 'max' => 250],
            [['sort'], 'default', 'value' =>50],
            [['status'], 'default', 'value' =>1],
            ['type','in', 'range' => [1, 2]],
            ['config','verifyConfig']
        ];
    }
    public function verifyConfig($attribute){
        $config = Json::decode($this->config,true);
        if( $this->type == 1 ){
            $feieyun = $config['feieyun'];
            if(empty($feieyun['user'])){
                $this->addError('config[feieyun][user]','请填写飞鹅用户名');
            }elseif(empty($feieyun['sn'])){
                $this->addError('config[feieyun][sn]','请填写打印机编号');
            }elseif(empty($feieyun['key'])){
                $this->addError('config[feieyun][key]','请填写打印机秘钥');
            }
        }else{
            $printcenter = $config['printcenter'];
            if(empty($printcenter['sn'])){
                $this->addError('config[printcenter][sn]','请填写打印机编号');
            }elseif(empty($printcenter['key'])){
                $this->addError('config[printcenter][key]','请填写打印机秘钥');
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
    public static $type = [
        1 => '飞鹅打印机',
        2 => '365云打印',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 17:17
     */
    public function getType(){
        return self::$type;
    }

    /**
     * @return mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 17:17
     */
    public function getTypeText(){
        if(isset(self::$type[$this->type])){
            return self::$type[$this->type];
        }else{
            return '--';
        }
    }

    public function beforeValidate()
    {
        $this->config = Json::encode($this->config);
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}
