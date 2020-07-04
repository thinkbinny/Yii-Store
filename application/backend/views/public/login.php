<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\captcha\Captcha;
$this->title = '管理员后台登录';

$this->registerJsFile('@web/static/jq-module/jquery.particleground.min.js',['depends'=>'assets\backendAsset','position'=>\yii\web\View::POS_HEAD]);
$css = <<<CSS
    html, body {width: 100%;height: 100%;overflow: hidden}
    body {background: #009688;}
    body:after {content:'';background-repeat:no-repeat;background-size:cover;-webkit-filter:blur(3px);-moz-filter:blur(3px);-o-filter:blur(3px);-ms-filter:blur(3px);filter:blur(3px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:-1;}
    .layui-container {width: 100%;height: 100%;overflow: hidden}
    .admin-login-background {width:360px;height:300px;position:absolute;left:50%;top:40%;margin-left:-180px;margin-top:-100px;}
    .logo-title {text-align:center;letter-spacing:2px;padding:14px 0;}
    .logo-title h1 {color:#009688;font-size:25px;font-weight:bold;}
    .login-form {background-color:#fff;border:1px solid #fff;border-radius:3px;padding:14px 20px;box-shadow:0 0 8px #eeeeee;}
    .login-form .layui-form-item {position:relative;}
    .login-form .layui-form-item label {position:absolute !important;left:1px;top:1px;width:38px;line-height:36px;text-align:center;color:#d2d2d2;}
    .login-form .layui-form-item label::after{content: '' !important;}
    .login-form .layui-form-item input {padding-left:36px;}
    .captcha {width:60%;display:inline-block;}
    .captcha-img {display:inline-block;width:34%;float:right;}
    .captcha-img img {border:1px solid #e6e6e6;height:36px;width:100%;}
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


<div class="layui-container">
    <div class="admin-login-background">
        <div class="layui-form login-form">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'layui-form']
            ]);?>
                <div class="layui-form-item logo-title">
                    <h1><?= Html::encode($this->title)?></h1>
                </div>
            <?
            echo $form->field($model, 'username', ['template' => '{label}{input}'])
                ->textInput(['class'=>'layui-input','autofocus' => true])
                ->label('<label class="layui-icon layui-icon-username"></label>');

            echo $form->field($model, 'password', ['template' => '{label}{input}'])
                ->passwordInput(['class'=>'layui-input'])
                ->label('<label class="layui-icon layui-icon-password"></label>');


            $Captcha = Captcha::widget([
                'name'=>'captchaimg',
                'captchaAction'=>'captcha',
                'imageOptions'=>[
                    'class'=>'vcode-img',
                    'id'=>'vcode-img',
                    'title'=>'换一个',
                    'alt'=>'换一个',
                    'style'=>'cursor:pointer;',
                    'onclick' => 'this.src=this.src+"&refresh=1&v="+Math.random();'
                ],
                'template'=>'{image}']);

            echo $form->field($model, 'verifyCode', ['template' => '{label}{input}<div class="captcha-img">'.$Captcha.'</div>'])
                ->textInput(['class'=>'layui-input verification captcha'])
                ->label('<label class="layui-icon layui-icon-vercode"></label>');

            echo $form->field($model, 'rememberMe', ['template' => '{input}'])
                ->checkbox(['lay-skin'=>'primary','title'=>'记住密码'],false);
            ?>

                <div class="layui-form-item">
                    <button type="submit" class="layui-btn layui-btn-fluid" >登 录</button>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>



