<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

$config = new \backend\models\Config();
$volist = $config->getMenuList();
if(empty($_GET['keyid']))
$_GET['keyid']= 'basic';
$data = array();
$data[] = ['label'=>'配置管理','url'=>['config/index']];

foreach ($volist as $val){
    $data[] = ['label'=>$val['title'],'url'=>['config/setup','id'=>$val['id']]];
}
echo Nav::widget([
    'items' => $data,
    'options' => ['class' => 'nav-tabs','style'=>'margin-bottom: 10px;'],
]);
?>