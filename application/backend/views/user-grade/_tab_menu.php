<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '等级管理',
            'url' => ['user-grade/index'],
        ],
        [
            'label' => '添加等级',
            'url' => ['user-grade/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '660px', height: '480px', title: '添加等级'}"
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);