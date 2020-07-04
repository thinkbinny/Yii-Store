<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_DIR') or define('YII_DIR', dirname(dirname(__DIR__)));
require(YII_DIR . '/vendor/autoload.php');
require(YII_DIR . '/vendor/yiisoft/yii2/Yii.php');
require(YII_DIR . '/common/config/bootstrap.php');
require(YII_DIR . '/application/weixin/config/bootstrap.php');
$config = yii\helpers\ArrayHelper::merge(
    require(YII_DIR . '/common/config/main.php'),
    require(YII_DIR . '/application/weixin/config/main.php')
);
(new yii\web\Application($config))->run();
