<?php
$params = array_merge(
    require(YII_DIR . '/common/config/params.php'),
    require(__DIR__.'/params.php')
);

return [
    'homeUrl'       =>'/mobile',
    'id'            => 'app-mobile',
    'basePath'      => dirname(__DIR__),
    'controllerNamespace' => 'mobile\controllers',
    'bootstrap'     => ['log'],
    'modules'       => [],    //模块
    'defaultRoute'  => 'index/index',   //默认路由
    'layout'        => 'main',//布局文件 优先级: 控制器>配置文件>系统默认
    //组件
    'components' => [
        //request组件
        'request' => [
            'baseUrl'   => '/mobile',
            'csrfParam' => '_csrf-mobile',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mobile_EJBy8WRohzpqJY7BTurjQaft2NV-g1cA',
        ],
        //身份认证类
        'user' => [
            'class'             => 'yii\web\User',
            'identityClass'     => 'mobile\models\Member',
            'enableAutoLogin'   => true,
            'identityCookie'    => ['name' => '_identity-mobile', 'httpOnly' => true],
            'loginUrl'          => ['public/login'], //配置登录url /public/logout
            'idParam'           =>'_identity_mobile_user',//这个是设置前台session的前缀
        ],

        //Rbac权限控制
        'authManager'   => [
            'class'     => 'yii\rbac\DbManager',
        ],
        // assetManager    样式
        'assetManager'  => [
            'bundles'   => [
                //'yii\bootstrap\BootstrapAsset'=>true,
                'yii\web\JqueryAsset'=>[
                    'js' => [],  // 去除 jquery.js
                    'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                ],
                'yii\web\YiiAsset' => [
                    'js' => [],  // 去除 yii.js
                    'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                ],

                'yii\widgets\ActiveFormAsset' => [
                    'js' => [],  // 去除 yii.activeForm.js
                    'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                ],

                'yii\validators\ValidationAsset' => [
                    'js' => [],  // 去除 yii.validation.js
                    'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                ],
            ],
            'basePath'  =>'@root/caches/assets',
            'baseUrl'   =>'/caches/assets',
        ],
        'log' => [
            'traceLevel'    => YII_DEBUG ? 3 : 0,
            'targets'       => [
                [
                    'class'     => 'yii\log\FileTarget',
                    'levels'    => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction'       => 'public/error',

        ],

        'urlManager'=>require(__DIR__ . '/rules.php'),

    ],
    'params' => $params,
];
