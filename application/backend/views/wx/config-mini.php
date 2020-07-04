<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Nav;
$this->title = '小程序配置';
$this->params['breadcrumbs'][] = ['label' => '小程序管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['thisUrl'] = 'wx/mini-config';
$this->registerCss("
.layui-form-label{width:150px;}
.layui-form-item .layui-input-inline{width:500px;}
");
$this->registerJs("

");
?>
<div class="layuimini-container">
    <div class="layuimini-main">
   <?//=$this->render('_tab_config_menu');
   echo Nav::widget([
       'items' => [
           [
               'label' => '小程序配置',
               'url' => ['wx/mini-config'],
           ],

       ],
       'options' => ['class' => 'nav-tabs'],
   ]);
   ?>
    <div class="layui-main">
        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form target-form'],
        ]); ?>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">AppID</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[app_id]" value="<?=isset($formParams['app_id']) ? $formParams['app_id'] : '';?>"  type="text">
            </div>
            <!--<div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 微信公众号 &gt; 开发 &gt; 基本配置 &gt; AppID</div>-->
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">AppSecret</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[secret]" value="<?=isset($formParams['secret']) ? $formParams['secret'] : '';?>"  type="text">
            </div>
            <!--<div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 微信公众号 &gt; 开发 &gt; 基本配置 &gt; AppSecret</div>-->
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name"></label>
            <div style="font-size: 18px;font-weight: 700;color: #777">微信支付</div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">商户ID</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[payment][mch_id]" value="<?=isset($formParams['payment']['mch_id']) ? $formParams['payment']['mch_id'] : '';?>"  type="text">
            </div>

        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">商户KEY</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[payment][key]" value="<?=isset($formParams['payment']['key']) ? $formParams['payment']['key'] : '';?>"  type="text">
            </div>

        </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'ajax-post layui-btn','target-form'=>'target-form']) ?>
                </div>
            </div>
        <?php
        ActiveForm::end(); ?>
    </div>
  </div>
</div>