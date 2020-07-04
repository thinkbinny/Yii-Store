<?php
$params = array_merge(
    require(YII_DIR . '/common/config/params.php'),
    require(__DIR__.'/params.php')
);

return [
    'homeUrl'=>'/',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    //'defaultRoute' => 'index/index',   //默认路由
    'components' => [
        'request' => [
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => 'EJBy8WRohzpqJY7BTurjQaft2NV-g1cA',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'assetManager' => [
            'bundles' => [
                //'yii\bootstrap\BootstrapAsset'=>true,
                'yii\web\JqueryAsset'=>false,
            ],
            //'basePath' =>'@root/assets',
            'baseUrl'=>'@web/assets',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // 默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            'enablePrettyUrl' => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。
            'enableStrictParsing' => true,

            'showScriptName'=>false,
            'baseUrl' => '/',
            'suffix'=>'.html',
            'rules'=>[
                '/'=>'/site/index',
                '/<controller>/<action>'=>'/<controller>/<action>',
            ]
        ],

    ],
    'params' => $params,
];
