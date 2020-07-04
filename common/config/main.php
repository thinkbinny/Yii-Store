<?php
$db     = require(YII_DIR . '/config/db.php');

return [
    'vendorPath'    => YII_DIR . '/vendor',
    'language'      => 'zh-CN',  //目标语言
    'runtimePath'   => YII_DIR . '/caches/runtime',
    'timeZone'      =>'Asia/Shanghai',
    'components' => [
        'formatter'=>[
            'defaultTimeZone' => 'Asia/Shanghai',
            'dateFormat'=>'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.changet.cn',
                'username' => 'csm-noreply@changet.cn',
                'password' => 'yangyang123A',
                'port' => '465',//'25', 465 587
                'encryption' => 'ssl',//tls ssl
            ],'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['csm-noreply@changet.cn'=>'千单易®']
            ],



        ],
        'db' => [
            'class'         => 'yii\db\Connection',
            'dsn'           => 'mysql:host='.$db['host'].';dbname='.$db['dbname'],
            'username'      => $db['username'],
            'password'      => $db['password'],
            'charset'       => 'utf8',
            'tablePrefix'   => $db['tablePrefix'],
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'assetManager' => [
            //'basePath' => '@webroot/caches/assets',
            //'baseUrl'=>'@web/caches/assets',
            'bundles' => [
                'yii\web\YiiAsset',
                'yii\web\JqueryAsset',
                'yii\bootstrap\BootstrapAsset',
                // you can override AssetBundle configs here
            ],
            //'linkAssets' => true,
            // ...
        ],
        /**
         * 语言包配置
         * 将"源语言"翻译成"目标语言". 注意"源语言"默认配置为 'sourceLanguage' => 'en-US'
         * 使用: \Yii::t('common', 'title'); 将common/messages下的common.php中的title转为对应的中文
         */
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'common'   => 'common.php',
                        'frontend' => 'frontend.php',
                        'backend'  => 'backend.php',
                    ],
                ],
            ],
        ],
        'sms' => [
            // 中国云信
            'class'    => 'common\plugin\sms\AliyunSms',
            'username' => '',
            'password' => '',
            'fileMode' => false
        ],


    ],
    'aliases' => array_merge([
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],require(YII_DIR . '/common/components/extensions.php')),
    //'aliases' => require(YII_DIR . '/common/components/extensions.php'),

];
