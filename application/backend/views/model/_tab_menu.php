<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '模型列表',
            'url' => ['model/index'],
        ],
        [
            'label' => '添加模型',
            'url' => ['model/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '600px', height: '350px', title: '添加模型'}"
            ]

        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);