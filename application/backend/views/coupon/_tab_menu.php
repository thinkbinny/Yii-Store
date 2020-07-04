<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '优惠券列表',
            'url' => ['coupon/index'],
        ],
        [
            'label' => '领取记录',
            'url' => ['coupon/receive'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);