<?php
namespace backend\models;

use Yii;
use yii\base\Model;
//use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\rbac\Item;

class AuthItem extends Model {

    public $name;
    public $type;
    public $description;
    public $ruleName;
    //public $created_at;
    //public $updated_at;
    public $data;

    private $_item;

    public function __construct($item = null) {
        $this->_item = $item;
        if($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::ecode($item->data);
        }
    }

    //自动设置 created_at updated_at
    /*public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }*/

    public function rules() {
        return [
            [['name'], 'required'],
            //['name', 'unique'],
            ['name', 'match', 'pattern' => '/^[-_a-zA-Z0-9\/]+$/'],
            ['description', 'filter', 'filter' => function($value) {
                return Html::encode($value);
            }],
            [['type'], 'integer'],
            [['description', 'ruleName'], 'safe'],
            ['name','getUnique']
        ];
    }

    public function getUnique(){
        if( $this->type == 1 ){
            $authManager    = Yii::$app->authManager;
            $oldName = '';
            if($this->_item !== null) {
                $oldName = $this->_item->name;
            }
            $data           = array();
            foreach ($authManager->getRoles() as $val){
                if($val->name !== $oldName){
                    $data[] = $val->name;
                }
            }
            if(in_array($this->name,$data)){
                $this->addError('name','角色名称已存在');
                return false;
            }
        }
    }

    public function attributeLabels() {
        return [
            'name' => '角色标识',//角色名称
            'description' => '角色名称', //描述
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'type' => 'Type',
            'ruleName' => 'Rule Name',
            'data' => 'Data',
        ];
    }

    //检测记录是否为空
    public function getIsNewRecord() {
        return $this->_item === null;
    }

    public function save() {
        if(!$this->validate()) return false;
        $authManager = Yii::$app->authManager;
        if($this->_item !== null) {
            $isNew = false;
            $oldName = $this->_item->name;
        } else {
            $isNew = true;
            $this->_item = ($this->type == Item::TYPE_ROLE) ? $authManager->createRole($this->name) : $authManager->createPermission($this->name);
        }
        $this->_item->name = $this->name;
        $this->_item->type = $this->type;
        $this->_item->description = $this->description;
        if($this->ruleName) $this->_item->ruleName = $this->ruleName;
        //$this->_item->data = $this->data === null ? null : Json::ecode($this->data);
        if($isNew){
            $authManager->add($this->_item);
        }else{
            $authManager->update($oldName, $this->_item);

        }
        //Helper::invalidate();
        return true;
    }

    public function getItem() {
        return $this->_item;
    }

}
