<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;
$this->params['thisUrl']='model/index';
$model_id = Yii::$app->request->get('model_id');
echo Nav::widget([
    'items' => [
        [
            'label' => '字段列表',
            'url' => ['model-field/index','model_id'=>$model_id],
        ],
        [
            'label' => '添加字段',
            'url' => ['model-field/create','model_id'=>$model_id],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '1000px', height: '90%', title: '添加字段',scrollbar:'Yes'}"
            ]
        ],
       /* [
            'label' => '生成正则',
            'url' => ['model-field/regular','model_id'=>$model_id],
        ],*/
    ],

    'options' => ['class' => 'nav-tabs'],
]);