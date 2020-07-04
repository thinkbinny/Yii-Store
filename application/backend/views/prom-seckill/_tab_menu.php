<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '抢购活动列表',
            'url' => ['prom-seckill/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);