<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Nav;
$this->title = '公众号配置';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['config','type'=>'config']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['thisUrl'] = 'wx/mp-config';
$this->registerCss("
.layui-form-label{width:150px;}
.layui-form-item .layui-input-inline{width:500px;}
");

?>
<div class="layuimini-container">
    <div class="layuimini-main" >
   <?//=$this->render('_tab_config_menu');
   echo Nav::widget([
       'items' => [
           [
               'label' => '公众号配置',
               'url' => ['wx/mp-config'],
           ],

       ],
       'options' => ['class' => 'nav-tabs'],
   ]);
   ?>

        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form target-form'],
        ]); ?>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">AppID</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[app_id]" value="<?=isset($formParams['app_id']) ? $formParams['app_id'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> <a style="color: #0099cc;" target="_blank" href="https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=1923033027&lang=zh_CN"> 微信公众号</a>
                &gt; 开发 &gt; 基本配置 &gt; AppID</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">AppSecret</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[secret]" value="<?=isset($formParams['secret']) ? $formParams['secret'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> <a style="color: #0099cc;" target="_blank" href="https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=1923033027&lang=zh_CN"> 微信公众号</a> &gt; 开发 &gt; 基本配置 &gt; AppSecret</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name"></label>
            <div style="font-size: 18px;font-weight: 700;color: #777">服务器配置</div>
        </div>
        <div ></div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">服务器地址(URL)</label>
            <div  class="layui-input-inline">
                <?php
                if(isset($formParams['url']) && !empty($formParams['url'])){
                    $url = $formParams['url'];
                } else{
                    $url = \yii\helpers\Url::to('weixin',true);
                }

                ?>
                <input class="layui-input" name="form[url]" value="<?=$url?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 必须以http://或https://开头</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">令牌(Token)</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[token]" value="<?=isset($formParams['token']) ? $formParams['token'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 必须为英文或数字，长度为3-32字符。</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">EncodingAESKey</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[encodingAESKey]" value="<?=isset($formParams['encodingAESKey']) ? $formParams['encodingAESKey'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 消息加密密钥由43位字符组成，可随机修改，字符范围为A-Z，a-z，0-9。 </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">消息加解密方式</label>
            <div  class="layui-input-inline">
                <input name="form[safeMode]" value="0" title="明文模式" <?php if(isset($formParams['safeMode']) && $formParams['safeMode'] == 0 || empty($formParams['safeMode'])) echo 'checked';?> type="radio">
                <input name="form[safeMode]" value="1" title="兼容模式" <?php if(isset($formParams['safeMode']) && $formParams['safeMode'] == 1) echo 'checked';?> type="radio">
                <input name="form[safeMode]" value="2" title="安全模式（推荐）" <?php if(isset($formParams['safeMode']) && $formParams['safeMode'] == 2) echo 'checked';?> type="radio">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name"></label>
            <div style="font-size: 18px;font-weight: 700;color: #777">微信网页授权</div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">授权范围</label>
            <div  class="layui-input-inline">
                <input name="form[oauth][scopes]" value="snsapi_base" title="基础(BASE)" <?php if(isset($formParams['oauth']['scopes']) && $formParams['oauth']['scopes'] == 'snsapi_base') echo 'checked';?> type="radio">
                <input name="form[oauth][scopes]" value="snsapi_userinfo" title="用户信息(USERINFO)" <?php if(isset($formParams['oauth']['scopes']) && $formParams['oauth']['scopes'] == 'snsapi_userinfo' || empty($formParams['oauth']['scopes'])) echo 'checked';?> type="radio">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">授权回调</label>
            <div  class="layui-input-inline">
              <input class="layui-input" name="form[oauth][callback]" value="<?=isset($formParams['oauth']['callback']) ? $formParams['oauth']['callback'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 授权后返回页面。必须以http://或https://开头 </div>
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
            <label class="layui-form-label" for="menu-name">支付通知页面</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[payment][notify_url]" value="<?=isset($formParams['payment']['notify_url']) ? $formParams['payment']['notify_url'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 支付通知返回结果处理页面。必须以http://或https://开头 </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">证书路径</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[payment][cert_path]" value="<?=isset($formParams['payment']['cert_path']) ? $formParams['payment']['cert_path'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 从站点跟目录算起 </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="menu-name">KEY路径</label>
            <div  class="layui-input-inline">
                <input class="layui-input" name="form[payment][key_path]" value="<?=isset($formParams['payment']['key_path']) ? $formParams['payment']['key_path'] : '';?>"  type="text">
            </div>
            <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 从站点跟目录算起 </div>
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