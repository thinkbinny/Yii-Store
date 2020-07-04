<?php
namespace backend\models;

use common\components\Func;
use Yii;
use common\libs\Tree;
use yii\helpers\Url;

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
class Menu extends \yii\db\ActiveRecord
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
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','url','icon_style'], 'trim'],
            [['pid','name'], 'required'],
            [['pid', 'display'], 'integer'],
            [['name', 'icon_style'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 60],
            ['sort', 'default', 'value' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '上级菜单',
            'name' => '菜单名称',
            'url' => 'URL',
            'icon_style' => '图标样式',
            'display' => '是否显示',
            'sort' => '排序',
        ];
    }

    public function getDisplays() {
        return self::$displays;
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

    public function getUrlModel($volist,$i=1){
        $i++;

        $data = array();
        foreach ($volist as $key => $vo){

            $info = [
                'id'        => $vo['id'],
                'pid'       => $vo['pid'],
                'title'     => $vo['name'],
                'icon'      => $vo['icon_style'],
                'target'    => '_self',
                'url'       => $vo['url'],
            ];
            if(!empty($vo['url'])){
                //$info['href']  = Url::to([$vo['url']]);
                $url = explode('?',$vo['url']);
                //$url = $vo['url'];
                if(count($url) > 1){
                    $param = array();
                    $param[] = $url[0];
                    $param[strstr($url[1], '=', true)] = substr(strstr($url[1], '='), 1);
                    $info['href']  = Url::to($param);
                }else{
                    $info['href']  = Url::to([$vo['url']]);
                }
            }


            if(isset($vo['child'])){
                if($i<3){
                    $info['child'] = self::getUrlModel($vo['child'],$i);
                }

            }
            $data[$key] = $info;
        }
        return $data;
    }


    public function getFindModel(){
        $volist = self::getRbac();//static::find()->where(['display' => 1])->asArray()->all();

        $data   = array();
        $volist = Func::list_to_tree($volist,$pk='id',$pid='pid',$child='child',$root=0);
        foreach ($volist as $key => $vo){
            $info = [
                'id'        => $vo['id'],
                'pid'       => $vo['pid'],
                'title'     => $vo['name'],
                'icon'      => $vo['icon_style'],
                'target'    => '_self',
                'url'       => $vo['url'],
            ];
            if(!empty($vo['url'])){
                $url = explode('?',$vo['url']);
                //$url = $vo['url'];
                if(count($url) > 1){
                    $param = array();
                    $param[] = $url[0];
                    $param[strstr($url[1], '=', true)] = substr(strstr($url[1], '='), 1);
                    $info['href']  = Url::to($param);
                }else{
                    $info['href']  = Url::to([$vo['url']]);
                }
            }
            if(isset($vo['child'])){
                $info['child'] = self::getUrlModel($vo['child'],1);
            }
            $data[$key] = $info;
        }
        return [
            'clearInfo'=>[
                'clearUrl'=>'',
            ],
            'homeInfo'=>[
                'title' =>'首页',
                'icon'  =>'fa fa-home',
                'href'  =>Url::to(['index/index']),
            ],
            'logoInfo'=>[
                'title' =>'管理控制台',
                'image'  =>Yii::$app->params['assetsUrl'].'/images/logo.png',
                'href'  =>Url::to(['index/iframe']),
            ],
            'menuInfo'=>$data,
        ];
    }

    private static function getRbac(){
        $data = self::getMenu();
        if(Yii::$app->user->identity->id == 1){ //超级管理员跳过
            return $data;
        }
        $key        = Yii::$app->params['LOGIN_MENU_RBAC'].Yii::$app->user->identity->id;
        $meun       = Yii::$app->cache->get($key);
        if(empty($meun)){
            $routes = self::getPermissionsByUser();
            $meun   = array();
            foreach ($data as $vo){
                if(!empty($vo['url'])){
                    if(in_array($vo['url'],$routes)){
                        //有权限
                        $meun[] = $vo;
                    }
                }else{
                    //跳过判断权限
                    $meun[] = $vo;
                }
            }
            //写入权限 1个小时验证一次
            Yii::$app->cache->set($key,$meun);
        }
        return $meun;
    }

    /**
     * 删除缓存
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/5/2 10:14
     */
    public static function deleteRbacCache($id = 0){
        if(empty($id)){
            $id = Yii::$app->user->identity->id;
        }
        $key        = Yii::$app->params['LOGIN_MENU_RBAC'].$id;
        Yii::$app->cache->delete($key);
    }
    /**
     * @return array|mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/8/24 21:09
     */
    public static function getMenu() {
        $data = false;//Yii::$app->cache->get('MenuVolist');
        if($data===false){
            $data      = static::find()->where(['display' => 1])->indexBy('id')->asArray()->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])->all();
            Yii::$app->cache->set('MenuVolist',$data);
        }
        return $data;

    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/9/5 16:04
     */
    private static function getPermissionsByUser(){
        $a = Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->id);
        $routes = [];
        foreach ($a as $name => $value) {
            $routes[] = $name;
        }
        $routes = array_unique($routes);
        sort($routes);
        return $routes;
    }


    /**
     * 保存之后更新
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete('MenuVolist');
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
