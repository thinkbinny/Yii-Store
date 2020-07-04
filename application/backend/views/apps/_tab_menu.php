<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '应用列表',
            'url' => ['apps/index'],
        ],
        [
            'label' => '添加应用',
            'url' => ['apps/create'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);