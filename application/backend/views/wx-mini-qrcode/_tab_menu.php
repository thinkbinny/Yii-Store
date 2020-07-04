<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '小程序码列表',
            'url' => ['wx-mini-qrcode/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);