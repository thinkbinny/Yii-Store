<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '模板列表',
            'url' => ['wx-tpl-msg/index'],
        ],
        [
            'label' => '添加模板',
            'url' => ['wx-tpl-msg/create'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);