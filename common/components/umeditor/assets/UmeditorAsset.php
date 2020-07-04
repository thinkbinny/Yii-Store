<?php
/**
 * @link http://www.yii-china.com/
 * @copyright Copyright (c) 2015 Yii中文网
 */

namespace extensions\umeditor\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Xianan Huang <xianan_huang@163.com>
 */
class UmeditorAsset extends AssetBundle
{
    public $css = [
        'themes/default/css/umeditor.min.css'
    ];
    
    public $js = [
        'umeditor.config.js',
        'umeditor.min.js',
    ];

    public $depends = [
        'assets\AppAsset',

    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];

    /**
     * 初始化：sourcePath赋值
     * @see \yii\web\AssetBundle::init()
     */
    public function init()
    {
        $this->sourcePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR . 'vendor';
    }
}
