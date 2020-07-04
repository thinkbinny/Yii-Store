<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '退货地址',
            'url' => ['return-address/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);