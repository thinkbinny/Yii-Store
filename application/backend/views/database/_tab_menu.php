<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => '备份数据库',
            'url' => ['database/export'],
        ],
        [
            'label' => '还原数据库',
            'url' => ['database/import'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);