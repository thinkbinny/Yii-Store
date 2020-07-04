<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '商品列表',
            'url' => ['goods/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);