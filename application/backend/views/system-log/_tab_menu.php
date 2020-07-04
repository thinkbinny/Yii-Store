<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '日志列表',
            'url' => ['system-log/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);