<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '栏目列表',
            'url' => ['apps-menu/index'],
        ],
        [
            'label' => '添加栏目',
            'url' => ['apps-menu/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '700px', height: '350px', title: '添加栏目'}"
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);