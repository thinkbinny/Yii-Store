<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '角色管理',
            'url' => ['role/index'],
        ],
        [
            'label' => '添加角色',
            'url' => ['role/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '550px', height: '230px', title: '添加角色'}",
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);