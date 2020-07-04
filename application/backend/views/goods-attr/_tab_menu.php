<?php
use yii\bootstrap\Nav;


echo Nav::widget([
    'items' => [
        [
            'label' => '属性列表',
            'url' => ['goods-attr/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);