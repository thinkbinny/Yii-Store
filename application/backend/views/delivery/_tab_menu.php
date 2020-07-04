<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '运费模板',
            'url' => ['delivery/index'],
        ],

    ],
    'options' => ['class' => 'nav-tabs'],
]);