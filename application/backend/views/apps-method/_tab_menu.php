<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '方法列表',
            'url' => ['apps-method/index'],
        ],
        [
            'label' => '添加方法',
            'url' => ['apps-method/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '700px', height: '450px', title: '添加栏目'}"
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);