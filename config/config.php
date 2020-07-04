<?php
return [
    'app_key'   => 'C2YlPOE7AQuU48gH',
    'app_secret'=> 'w23e7StpAUx4fzY6Hl3iY1Vlmx1XVKM2',

    'private'   => 0,       //是否开启私有化  1:开启 0关闭  (关闭自动链接官网短信接口)
    //开启必须填写下面信息
    'KeyId'     => 'LTAI7jp37jPxTo38',
    'KeySecret' => 'AzrYg1ulr9iZMPk7funhAAdNkaETz4',
    'SignName'  => '千单易',  //短信签名
    'Template'  =>  [   //短信模板 只支持 1~3 参数
                1  => 'SMS_164512966',  //业主手机验证码
                2  => 'SMS_164512974',  //报价结果通知短信
                3  => 'SMS_172740116',  //您有新的业主装修预约，请登录系统查看详情，客户手机：${phone}
                4  => 'SMS_172735119',  //新 （您的预约/报名已成功，24小时内与您联系，为您确认解答。）
            ]
];