<?php
$params = array_merge(
    require(YII_DIR . '/common/config/params.php'),
    require(__DIR__.'/params.php')
);

return [
    'homeUrl'=>'/admin',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers', //控制器默认命名空间
    'bootstrap' => ['log'],
    'modules' => [],    //模块
    'defaultRoute' => 'index/index',   //默认路由
    'layout' => 'main',//布局文件 优先级: 控制器>配置文件>系统默认
    //组件
    'components' => [
        //request组件
        'request' => [
            'baseUrl' => '/admin',
            'csrfParam' => '_csrf-backend',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'EJBy8WRohzpqJY7BTurjQaft2NV-g1cA',
        ],
        //身份认证类
        'user' => [
            'class'             => 'yii\web\User',
            'identityClass'     => 'backend\models\Admin',
            'enableAutoLogin'   => true,
            'identityCookie'    => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl'          => ['public/login'], //配置登录url /public/logout
            'idParam'           =>'_identity_backend_admin',//这个是设置前台session的前缀
        ],
        'session' => [
            'class'         => 'yii\web\DbSession',
            'db'            => 'db',   //数据库连接的应用组件ID,默认为'db'
            'sessionTable'  => '{{%session}}', //session数据表名,默认为'session'
            'timeout'       =>  3600,//1个小时不动 自动 退出时间
        ],

        //Rbac权限控制
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        // assetManager    样式
        'assetManager' => [
            'bundles' => [
                //'yii\bootstrap\BootstrapAsset'=>true,
               'yii\web\JqueryAsset'=>false,
            ],
            //'basePath' =>'@root/assets',
            'baseUrl'=>'@web/assets',
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
        //'urlManagerFrontend'=>require(YII_DIR . '/application/frontend/config/rules.php'),
        /*'urlManager' => [
            'enablePrettyUrl' => true,  //开启url规则
            'showScriptName' => false,  //是否显示url中的index.php
            'suffix' => '.html',    //后缀
            'rules' => [
            ],
        ],*/
    ],
    'params' => $params,
];
