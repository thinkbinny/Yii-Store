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
class Ie9Asset extends AssetBundle
{
   // public $basePath = '@app/assets';
   // public $baseUrl = '@web';
    public $basePath = '@app';
    public $sourcePath = '@assets/static';
    public $css = [

    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
       // 'condition' => 'It IE 9',
    ];
    public $js = [
        'js/html5.min.js',
        'js/respond.min.js',
    ];
    public $depends = [
    ];
}
