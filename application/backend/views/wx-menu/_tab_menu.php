<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '菜单列表',
            'url' => ['wx-menu/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);