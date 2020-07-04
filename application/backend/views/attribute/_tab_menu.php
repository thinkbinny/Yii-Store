<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => '模型列表',
            'url' => ['attribute/index','model_id'=>$model->model_id],
        ],
        [
            'label' => '添加模型',
            'url' => ['attribute/create','model_id'=>$model->model_id],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);