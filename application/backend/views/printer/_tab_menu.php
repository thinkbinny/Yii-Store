<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '小票打印机',
            'url' => ['printer/index'],
        ],
        [
            'label' => '打印设置',
            'url' => ['printer/setting'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);