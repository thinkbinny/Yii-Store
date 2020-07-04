<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '文件列表',
            'url' => ['files/index'],
        ],
        [
            'label' => '图片库',
            'url' => ['files/browsefile'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '900px', height: '600px', title: '图片库',skin:'layui-layer-library'}"
            ]
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);