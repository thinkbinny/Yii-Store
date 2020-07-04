<?php
$params = array_merge(
    require(YII_DIR . '/common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [

    'homeUrl'=>'/weixin',
    'id' => 'app-weixin',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'weixin\controllers',
    'modules' => [],    //模块
    'defaultRoute' => 'index/index',   //默认路由
    'layout' => 'main',//布局文件 优先级: 控制器>配置文件>系统默认
    'components' => [
        'request' => [
            'baseUrl' => '/weixin',
            'csrfParam' => '_csrf-weixin',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'H9rnf_NVw3MrYndK9kT2YCcCPlXqk-ao',
        ],
        'user' => [
            'class'             => 'yii\web\User',
            'identityClass'     => 'weixin\models\User',
            'enableAutoLogin'   => true,
            'identityCookie'    => ['name' => '_identity-weixin', 'httpOnly' => true],
            'loginUrl'          => ['public/login'], //配置登录url /public/logout
            'idParam'           =>'_identity_weixin',//这个是设置前台session的前缀
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-weixin',
        ],
        'assetManager' => [
            'bundles' => [
                //'yii\bootstrap\BootstrapAsset'=>false,
                'yii\web\JqueryAsset'=>false,
            ],
            'basePath' =>'@webhomeroot/caches/assets',
            'baseUrl'=>'/caches/assets',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'public/error',
        ],
        'urlManager'=>require(__DIR__ . '/rules.php'),

    ],

    'params' => $params,
];
