<?php
namespace mobile\controllers;

use Yii;
use yii\helpers\Url;
use common\controllers\Controller;

use common\models\Config;
use yii\web\Response;
class BaseController extends Controller {



    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * 初始化配置信息
     * 网站配置或模板配置等
     */
    public function init() {
        parent::init();
        Yii::$app->params['assetsUrl'] = (new \assets\mobileAsset)->baseUrl;
        //$this->layout = '_main';
        Yii::$app->params['basic']  = Config::getConfigs('basic');
        Yii::$app->params['weixin']  = Config::getConfigs('weixin');


    }

}