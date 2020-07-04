<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '菜单列表',
            'url' => ['menu/index'],
        ],
        /*[
            'label' => '添加菜单',
            'url' => ['menu/create'],
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);