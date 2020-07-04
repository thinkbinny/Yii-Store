<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => '修改个人信息',
            'url' => ['admin/editinfo'],
        ],
        [
            'label' => '修改密码',
            'url' => ['admin/resetpwd'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);