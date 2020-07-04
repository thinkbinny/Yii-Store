<?php

use yii\helpers\Html;
//use yii\bootstrap\ActiveForm;
use backend\widgets\ActiveForm;
use yii\captcha\Captcha;
$this->title = '谷歌二次验证';
$assets_url  = (new assets\backendAsset)->baseUrl;
$this->registerJsFile($assets_url.'/jq-module/jquery.particleground.min.js',['depends'=>'assets\backendAsset','position'=>\yii\web\View::POS_HEAD]);
$css = <<<CSS
    html, body {width: 100%;height: 100%;overflow: hidden}
    body {background: #009688;}
    body:after {content:'';background-repeat:no-repeat;background-size:cover;-webkit-filter:blur(3px);-moz-filter:blur(3px);-o-filter:blur(3px);-ms-filter:blur(3px);filter:blur(3px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:-1;}
    .layui-container .layui-container {width: 100%;height: 100%;overflow: hidden}
    .admin-login-background {width:360px;height:300px;position:absolute;left:50%;top:40%;margin-left:-180px;margin-top:-100px;}
    
   .google-form{top: 38% !important; left: 50% !important;margin-left: -152px !important;}


    .lock-screen {
        padding: 20px;
        overflow: hidden;
        display: block;
    }
    .lock-screen input {
        float: left;
        width: 180px;
        background-color: #009688;
        border-color: #009688;
        color: #fff;
        font-size: 16px;
    }
.lock-screen button {
    float: left;
    margin-left: 20px;
}
CSS;
$this->registerCss($css);

$js = <<<JS
    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer;
        // 登录过期的时候，跳出ifram框架
        if (top.location != self.location) top.location = self.location;

        // 粒子线条背景
        $(document).ready(function(){
            $('.layui-container').particleground({
                dotColor:'#5cbdaa',
                lineColor:'#5cbdaa'
            });
        });
    });
JS;

$this->registerJs($js);

?>
<div class="layui-container" style="height: 100%;width:auto;">
    <div class="admin-login-background">
        <div class="layui-layer google-form" >
            <div id="" class="layui-layer-content" >
                <div class="lock-screen" >
                    <?php
                    $form = ActiveForm::begin([
                        'options' => ['class' => 'layui-form']
                    ]);
                        echo $form->field($model, 'captcha', ['template' => '{input}','options'=>['style'=>'display: inline']])
                            ->textInput(['class'=>'layui-input','autofocus' => true, 'placeholder' => '请输入谷歌二次验证码'])
                            ->label(false);

                        //echo Html::input('text','captcha','',['class'=>'layui-input','placeholder'=>'请输入谷歌二次验证码']);
                        echo Html::submitButton('提交',['class'=>'layui-btn']);
                    ActiveForm::end();
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
