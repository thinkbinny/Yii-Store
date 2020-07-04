<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '用户列表',
            'url' => ['user/index'],
        ],
      /*  [
            'label' => '添加用户',
            'url' => ['user/create'],
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);