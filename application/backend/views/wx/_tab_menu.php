<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '粉丝列表',
            'url' => ['wx/index'],
        ],
       /* [
            'label' => '黑名单',
            'url' => ['links/create'],
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);