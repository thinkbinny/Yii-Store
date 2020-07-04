<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '明细列表',
            'url' => ['user-property/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);