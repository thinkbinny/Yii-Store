<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '核销记录列表',
            'url' => ['store-order-check/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);