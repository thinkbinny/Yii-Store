<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '物流公司',
            'url' => ['logistics/index'],
        ],
        [
            'label' => '物流编码表',
            'url' => ['logistics/view'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);