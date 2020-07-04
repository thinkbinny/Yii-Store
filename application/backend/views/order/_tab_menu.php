<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => '订单列表',
            'url' => [$this->params['thisUrl']],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);