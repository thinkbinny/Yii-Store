<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;
echo Nav::widget([
    'items' => [
        [
            'label' => '管理员管理',
            'url' => ['admin/index'],
        ],
        [
            'label' => '添加管理员',
            'url' => ['admin/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '660px', height: '350px', title: '添加管理员'}"
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);