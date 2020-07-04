<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '幻灯片列表',
            'url' => ['slides/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);