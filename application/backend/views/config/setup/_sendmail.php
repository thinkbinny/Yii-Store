<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;


?>
      <div class="layui-main">

        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form target-form'],
        ]);
        if(!isset($model->data['mail_type'])){
            $data = $model->data;
            $data['mail_type'] = 'smtp';
            $model->data = $data;
        }
        echo $form->field($model, 'data[mail_type]')->radioList(['smtp'=>'通过 SOCKET 连接 SMTP 服务器发送'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])->label('发送方式');

        echo $form->field($model,'data[smtp_server]')
            ->textInput(['placeholder'=>'请填写SMTP 服务器地址'])
            ->label('SMTP 服务器地址')
            ->hint('<i class="fa fa-info-circle"></i> 默认smtp.qq.com')
            ->width(500);
        echo $form->field($model,'data[smtp_port]')
            ->textInput(['placeholder'=>'请填写SMTP 端口'])
            ->label('SMTP 端口')
            ->hint('<i class="fa fa-info-circle"></i> 默认25端口')
            ->width(500);

        echo $form->field($model, 'data[auth]')->radioList([1=>'是',0=>'否'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])->label('SMTP 身份验证');



        if($supportSsl) {
            echo $form->field($model, 'data[openssl]')->radioList([1 => '是', 0 => '否'], [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? "checked" : "";
                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                    return $return;
                }
            ])->label('使用SSL加密方式')
                ->hint('<i class="fa fa-info-circle"></i> 您的服务器不支持ssl，请安装php扩展openssl');
        }else{
            echo $form->field($model, 'data[openssl]')->radioList([1 => '是', 0 => '否'], [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? "checked" : "";
                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                    return $return;
                }
            ])->label('使用SSL加密方式');
        }

        echo $form->field($model,'data[smtp_user]')
            ->textInput(['placeholder'=>'请填写邮箱用户名'])
            ->label('邮箱用户名')
            //->hint('<i class="fa fa-info-circle"></i> 默认25端口')
            ->width(500);

        echo $form->field($model,'data[smtp_pwd]')
            ->textInput(['placeholder'=>'请填写邮箱密码'])
            ->label('邮箱密码')
            ->width(500);

        echo $form->field($model,'data[send_email]')
            ->textInput(['placeholder'=>'请填写发件人邮箱'])
            ->label('发件人邮箱')
            ->width(500);

        echo $form->field($model,'data[nickname]')
            ->textInput(['placeholder'=>'请填写发件人昵称'])
            ->label('发件人昵称')
            ->width(500);

        echo $form->field($model,'data[sign]')
            ->textInput(['placeholder'=>'请填写邮件签名'])
            ->label('邮件签名')
            ->width(500);
        ?>

        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline">
                <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'ajax-post layui-btn','target-form'=>'target-form']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
