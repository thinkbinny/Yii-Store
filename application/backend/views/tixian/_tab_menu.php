<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '提现申请',
            'url' => ['tixian/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);