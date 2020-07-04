<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '链接列表',
            'url' => ['links/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);