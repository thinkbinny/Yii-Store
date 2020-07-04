<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'items' => [
        [
            'label' => '素材列表',
            'url' => ['wx-materials/index'],
        ],
      /*  [
            'label' => '更新素材',
            'url' => ['wx-materials/create'],
        ],*/
    ],
    'options' => ['class' => 'nav-tabs'],
]);