<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => '退换货订单',
            'url' => ['order-refund/index'],
        ],
        [
            'label' => '退款订单',
            'url' => ['order-refund/order'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);