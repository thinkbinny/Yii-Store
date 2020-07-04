<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;
$this->params['thisUrl'] = 'wx/config';
$type = Yii::$app->request->get('type');
if(!isset($type)){
    $url = ['wx/config'];
}else{
    $url = ['wx/config','type'=>'mp'];
}
echo Nav::widget([
    'items' => [
        [
            'label' => '公众号配置',
            'url' => $url,
        ],
        [
            'label' => '小程序配置',
            'url' => ['wx/config','type'=>'mini'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);