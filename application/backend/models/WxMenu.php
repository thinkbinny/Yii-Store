<?php
namespace backend\models;

use common\components\Func;
use Yii;
use common\libs\Tree;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property string $url
 * @property string $group
 * @property integer $hide
 * @property integer $sort
 */
class WxMenu extends \yii\db\ActiveRecord
{

    const DISPLAY = 1;
    const HIDE = 0;

    public static $displays = [
        self::DISPLAY => '显示',
        self::HIDE => '隐藏',
    ];

    public static $displayStyles = [
        self::HIDE => 'label-warning',
        self::DISPLAY => 'label-info',
    ];

    public static $type = [
        'view'                  =>  '链接',
        'click'                 =>  '触发关键字',
        'scancode_push'         =>  '扫码推事件',
        'scancode_waitmsg'      =>  '扫码带提示',
        'pic_sysphoto'          =>  '系统拍照发图',
        'pic_photo_or_album'    =>  '拍照或者相册发图',
        'pic_weixin'            =>  '微信相册发图',
        'location_select'       =>  '发送位置',
        'media_id'              =>  '图片（MEDIA_ID类型）',
        'view_limited'          =>  '图文消息（VIEW_LIMITED类型）',
        'miniprogram'           =>  '小程序',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->display = self::DISPLAY;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid','name','sort'], 'required'],
            [['pid', 'display', 'sort'], 'integer'],
            [['name','media_id','key','appid','pagepath'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'pid'       => '上级菜单',
            'name'      => '菜单名称',
            'url'       => '菜单URL',
            'type'      => '菜单类型',
            'key'       => '菜单KEY值',
            'media_id'  => 'MEDIA ID',
            'display'   => '是否显示',
            'sort'      => '排序',
            'appid'     => 'appid',
            'pagepath'  => '页面路径',
        ];
    }

    private function url_exists($url) {
        if(!preg_match('/http(s)?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
            return false;
        }
        return true;
    }
   private function utf8_strlen($str) {
        $count = 0;
        for($i = 0; $i < strlen($str); $i++){
            $value = ord($str[$i]);
            if($value > 127) {
                $count++;
                if($value >= 192 && $value <= 223) $i++;
                elseif($value >= 224 && $value <= 239) $i = $i +2;
                elseif($value >= 240 && $value <= 247) $i = $i +3;
                else die('Not a UTF-8 compatible string');
            }
            $count++;
        }
        return $count*2;
    }

    public function beforeValidate()
    {
        if($this->isNewRecord){
            $id = 0;
        }else{
            $id = $this->id;
        }
            if(empty($this->pid)){
                $count = $this->find()
                    ->where("pid=:pid and id<>:id")
                    ->addParams([':pid'=>0,':id'=>$id])
                    ->count();
                if($count>=3){
                    $this->addError('name','最多可以创建三个一级菜单');
                    return false;
                }
            }else{
                $count = $this->find()
                    ->where("pid=:pid and id<>:id")
                    ->addParams([':pid'=>$this->pid,':id'=>$id])
                    ->count();
                if($count>=5){
                    $this->addError('name','最多可以创建五个二级菜单');
                    return false;
                }
            }


        if(empty($this->name)){
            $this->addError('name','菜单名称不能为空。');
            return false;
        }elseif($this->pid==0 && $this->utf8_strlen($this->name)>32){
            $this->addError('name','一级菜单不超过4个汉字或8个英文。');
            return false;
        }elseif($this->pid>0 && $this->utf8_strlen($this->name)>60){
            $this->addError('name','子菜单不超过60个字节(15汉字或30个英文)');
            return false;
        }
        $isKey = [
          'media_id','view_limited','media_id','view','miniprogram'
        ];
        if($this->type == 'view' || $this->type == 'miniprogram'){
            if(empty($this->url)){
                $this->addError('url','菜单URL不能为空');
                return false;
            }elseif(!$this->url_exists($this->url)){
                $this->addError('url','必须以http://或https://开头的网址');
                return false;
            }
        }elseif(!in_array($this->type,$isKey)){
            if(empty($this->key)){
                $this->addError('key','菜单KEY值不能为空');
                return false;
            }elseif($this->utf8_strlen($this->key)>128){
                $this->addError('key','网址不能超过128字节（64个字符串）');
                return false;
            }
        }elseif($this->type == 'media_id' || $this->type == 'view_limited'){
            if(empty($this->media_id)){
                $this->addError('media_id','MEDIA ID不能为空');
                return false;
            }
        }elseif($this->type == 'miniprogram'){
            if(empty($this->appid)){
                $this->addError('appid','小程序的appid不能为空');
                return false;
            }
            if(empty($this->pagepath)){
                $this->addError('pagepath','小程序的页面路径不能为空');
                return false;
            }
        }

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function getDisplays() {
        return self::$displays;
    }
    public function getType() {
        return self::$type;
    }
    public static function getTypeText($type){
        return self::$type[$type];
    }
    /**
     * 获取菜单状态
     */
    public static function getDisplayText($display) {
        return self::$displays[$display];
    }

    /**
     * 获取菜单状态样式
     */
    public static function getDisplayStyle($display) {


        //return self::$displayStyles[$display];
    }

    /**
     *
     */
    protected function menu($pid){
        $volist = $this->find()
            ->where("pid=:pid AND display=:display")
            ->addParams([':pid'=>$pid,':display'=>1])
            ->asArray()
            ->orderBy("sort asc,id asc")
            ->all();
        return $volist;
    }
    protected function makeButton($data){

        return [
            'type'      =>$data['type'],
            'name'      =>$data['name'],
            'key'       =>$data['key'],
            'url'       =>$data['url'],
            'media_id'  =>$data['media_id'],
            'appid'     =>$data['appid'],
            'pagepath'  =>$data['pagepath'],
            'sub_button'=>[],
        ];
    }

    public function make(){
        $volist = $this->menu(0);
        $button = array();
        if(!empty($volist)):
            foreach ($volist as $key=> $vo){
                $info       =   $this->makeButton($vo);
                $subList    =   $this->menu($vo['id']);
                $sub_button =   array();
                if(!empty($subList)){
                    foreach ($subList as $v){
                        $sub_button[]    = $this->makeButton($v);
                    }
                }
                $info['sub_button'] = $sub_button;
                $button[] = $info;
            }
        endif;
        return [
            'button'=>$button,
        ];
    }
}
