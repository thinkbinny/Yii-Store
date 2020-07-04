<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '积分明细',
            'url' => ['integral/index'],
        ],
        [
            'label' => '积分设置',
            'url' => ['integral/setting'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);