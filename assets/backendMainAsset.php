<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class backendMainAsset extends AssetBundle
{
    public $basePath = '@app';
    public $baseUrl = '@web/static';
    public $css = [
        //'Font-Awesome/css/font-awesome.css',
        'Font-Awesome/css/all.min.css',
        'css/main.css',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
    ];
    public $js = [
        'js/main.js',
    ];
    public $depends = [
        'assets\AppAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
