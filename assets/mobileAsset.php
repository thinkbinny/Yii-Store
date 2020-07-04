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

class mobileAsset extends AssetBundle
{
    public $basePath = '@app';
    public $sourcePath = '@web/static';
    public $css = [
        '//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css',
        '//at.alicdn.com/t/font_1648712_ebaxms9y9wm.css',
        'css/main.css',
    ];
    public $jsOptions = [
       // 'position' => \yii\web\View::POS_HEAD,
    ];
    public $js = [
        '//g.alicdn.com/sj/lib/zepto/zepto.min.js',
        '//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js',
        'js/main.js',
    ];
   /* public $depends = [
        'assets\AppAsset',
        'yii\bootstrap\BootstrapAsset',
    ];*/
    
}
