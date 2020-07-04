<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '店员列表',
            'url' => ['store-clerk/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);