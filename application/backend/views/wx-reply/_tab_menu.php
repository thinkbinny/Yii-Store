<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '关键词自动回复',
            'url' => ['wx-reply/index'],
        ],
        [
            'label' => '消息自动回复',
            'url' => ['wx-reply/autoreply'],
        ],
        [
            'label' => '关注时自动回复',
            'url' => ['wx-reply/subscribe'],
        ],

       /* [
            'label' => '添加链接',
            'url' => ['links/create'],
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);