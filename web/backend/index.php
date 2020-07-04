<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_DIR') or define('YII_DIR', dirname(dirname(__DIR__)));

require(YII_DIR . '/vendor/autoload.php');
require(YII_DIR . '/vendor/yiisoft/yii2/Yii.php');
require(YII_DIR . '/common/config/bootstrap.php');
require(YII_DIR . '/application/backend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(YII_DIR . '/common/config/main.php'),
    require(YII_DIR . '/application/backend/config/main.php')
);
if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}
(new yii\web\Application($config))->run();
