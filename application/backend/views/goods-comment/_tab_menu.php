<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '评价列表',
            'url' => ['goods-comment/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);