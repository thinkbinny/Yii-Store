<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;


echo Nav::widget([
    'items' => [
        [
            'label' => '分类列表',
            'url' => ['goods-category/index'],
        ],
        /*[
            'label' => '添加栏目',
            'url' => ['category/create'],
            'linkOptions'=>[
                'class'=>'ajax-iframe-popup',
                'data-iframe'=>"{width: '1000px', height: '90%', title: '添加栏目',scrollbar:'Yes'}"
            ]
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);