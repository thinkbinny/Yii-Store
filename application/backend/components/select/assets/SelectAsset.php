<?php
/**
 * @link http://www.yii-china.com/
 * @copyright Copyright (c) 2015 Yii中文网
 */

namespace backend\components\select\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Xianan Huang <xianan_huang@163.com>
 */
class SelectAsset extends AssetBundle
{
    public $css = [
        'css/select.css'
    ];
    
    public $js = [

        'js/select.js',
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
        $this->sourcePath = dirname(__FILE__).DIRECTORY_SEPARATOR;
    }
}
