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

class AppAsset extends AssetBundle
{
     /*public $basePath = '@app';
     public $baseUrl = '/assets/static';*/
    public $basePath = '@app';
    public $sourcePath = '@assets/static';
    public $css = [
        'layui/css/layui.css',
    ];
    public $js = [
        'js/jquery.min.js',
        'layui/layui.js',
    ];
 /*   public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];*/

    public $depends = [
        'assets\Ie9Asset',
    ];
}
