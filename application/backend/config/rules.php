<?php
return [
    'class' => 'yii\web\UrlManager',
    // 默认不启用。但实际使用中，特别是产品环境，一般都会启用。
    'enablePrettyUrl' => true,
    // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
    // 否则认为是无效路由。
    // 这个选项仅在 enablePrettyUrl 启用后才有效。
    'enableStrictParsing' => true,

    'showScriptName'=>false,
    'suffix'=>'.shtml',
    'rules'=>[
        //'/index'=>'/index/index',
        '/<controller>/<action>'=>'/<controller>/<action>',
        '/'=>'/index/index'
    ]
];