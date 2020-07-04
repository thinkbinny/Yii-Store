<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '消息列表',
            'url' => ['message/index'],
        ],
        [
            'label' => '添加消息',
            'url' => ['message/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '900px', height: '560px', title: '添加消息'}"
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);