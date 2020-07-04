<?php
$config     = require(YII_DIR . '/config/config.php');
return [
    'adminEmail'        => 'admin@example.com',
    'supportEmail'      => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'dispatch_jump'     =>'@backend/views/layouts/dispatch_jump',
    'availableLocales'  => [
        'en-US'         =>'English (US)',
        'zh-CN'         => '简体中文',
    ],



    'USER_ADMIN_KEY_CACHE'      => 'sys_admin_user_list',
    'USER_ADMIN_MAX_CACHE'      => 1000,
    'USER_KEY_CACHE'            => 'sys_active_user_list',
    'USER_MAX_CACHE'            => 1000,

    'USER_NICKNAME_KEY_CACHE'   => 'sys_user_nickname_list',
    'USER_NICKNAME_MAX_CACHE'   => 1000,

    'WX_TPL_MSG_KEY_CACHE'      => 'wx_tpl_msg_cache',//微信消息模板缓存

    'ACTION'                    => 'action', //cate_alias

    'CATEGORY_TYPE'             =>[
        ['id' =>0 ,'title'=>'系统首页',                   'route'=>'/index/index'],
        ['id' =>10 ,'title'=>'列表类型(最新活动)',         'route'=>'/activity/index'],//
        ['id' =>11 ,'title'=>'列表类型(最新动态)',         'route'=>'/article/new'],//
        ['id' =>12 ,'title'=>'列表类型(合作品牌)',         'route'=>'/brand/index'],
        ['id' =>13 ,'title'=>'列表类型(施工工艺)',         'route'=>'/gongyi/index'],
        ['id' =>14 ,'title'=>'列表类型(装修攻略)',         'route'=>'/article/index'],//
        ['id' =>15 ,'title'=>'列表类型(设计团队)',         'route'=>'/shejishi/index'],
        ['id' =>16 ,'title'=>'列表类型(工长团队)',         'route'=>'/gongzhang/index'],
        ['id' =>17 ,'title'=>'列表类型(装修案例)',         'route'=>'/anli/index'],//
        ['id' =>18 ,'title'=>'列表类型(热门小区)',         'route'=>'/xiaoqu/index'],//
        ['id' =>19 ,'title'=>'列表类型(在建工地)',         'route'=>'/gongdi/index'],
        ['id' =>20 ,'title'=>'列表类型(3D样板间)',         'route'=>'/yangban/index'],
        ['id' =>50 ,'title'=>'单页类型(关于我们)',          'route'=>'/article/about'],
        ['id' =>61 ,'title'=>'业主提交(免费快速报价)',              'route'=>'/baojia/index'],
        ['id' =>62 ,'title'=>'业主提交(免费专车接送)',              'route'=>'/baojia/zhuanche'],
        ['id' =>63 ,'title'=>'业主提交(免费上门验房)',              'route'=>'/baojia/shangmen'],
        ['id' =>64 ,'title'=>'业主提交(免费预约设计)',              'route'=>'/baojia/yuyue'],
        ['id' =>100 ,'title'=>'链接',                        'route'=>''],


    ],
    //缓存KEY配置 ，指针对前台
    'CACHE_CTEGORY_LISTS' => 'cacheCtegoryLists',
    'CACHE_CTEGORY_MENU'  => 'cacheCtegoryMenu',
    'CACHE_LINKS_LISTS'   => 'CACHE_LINKS_LISTS',
    'CACHE_CONFIG'        => 'CACHE_CONFIG',

    'APPKEY'              => $config['app_key'],
    'APPSECRET'           => $config['app_secret'],
    /**
     * 阿里云  短信模板
     */
    'ALIYUN_SMS_PRIVATE'    => $config['private'],
    'ALIYUN_SMS_TEMPLATE'   => $config['Template'],
    'ALIYUN_SMS_SIGNNAME'   => $config['SignName'],


];
