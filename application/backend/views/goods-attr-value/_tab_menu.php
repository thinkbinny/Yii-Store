<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '门店列表',
            'url' => ['store/index'],
        ],
        /*[
            'label' => '添加分组',
            'url' => ['files-group/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '500px', height: '280px', title: '添加分组'}"
            ]
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);