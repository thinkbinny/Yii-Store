<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;

$this->registerCss("
.layui-form-label{width:16%;}
.layui-form-item .layui-input-inline{width:500px;}
");
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});
");
?>

        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form target-form'],
        ]);
        echo $form->field($model,'data[sitename]')
            ->textInput(['placeholder'=>'请填写网站名称'])
            ->label('网站名称')
            ->hint('<i class="fa fa-info-circle"></i> 一般不超过80个字符')
            ->width(500);

        echo $form->field($model,'data[url]')
            ->textInput(['placeholder'=>'请填写网站域名'])
            ->label('网站域名')
            ->hint('')
            ->width(500);
        echo $form->field($model,'data[logo]')
            ->textInput(['placeholder'=>'请填写网站logo'])
            ->label('网站logo')
            ->hint('')
            ->width(500);

        echo $form->field($model,'data[seo_title]')
            ->textInput(['placeholder'=>'请填写SEO标题'])
            ->label('SEO标题')
            ->hint('<i class="fa fa-info-circle"></i> 一般不超过200个字符，关键词用英文“ , ”、“ - ”、“ | ”隔开')
            ->width(500);
        echo $form->field($model,'data[seo_keywords]')
            ->textInput(['placeholder'=>'请填写SEO关键字'])
            ->label('SEO关键字')
            ->hint('<i class="fa fa-info-circle"></i> 一般不超过100个字符，关键词用英文逗号隔开')
            ->width(500);
        echo $form->field($model,'data[seo_description]')
            ->textarea(['placeholder'=>'请填写SEO描述'])
            ->label('SEO描述')
            ->hint('<i class="fa fa-info-circle"></i> 一般不超过200个字符')
            ->width(500);
        echo $form->field($model,'data[copyright]')
            ->textarea(['placeholder'=>'请填写版权信息'])
            ->label('版权信息')
            ->hint('')
            ->width(500);
        echo $form->field($model,'data[statcode]')
            ->textarea(['placeholder'=>'请填写访问统计代码'])
            ->label('访问统计代码')
            ->hint('')
            ->width(500);

        if(!isset($model->data['close'])){
            $data = $model->data;
            $data['close'] = 0;
            $model->data = $data;
        }elseif($model->data['close'] == null){
            $data = $model->data;
            $data['close'] = 0;
            $model->data = $data;
        }

        echo $form->field($model, 'data[close]')->radioList([1=>'是',0=>'否'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])->label('是否关闭网站');

        echo $form->field($model,'data[close_reason]')
            ->textarea(['placeholder'=>'请填写关闭原因'])
            ->label('关闭原因')
            ->hint('')
            ->width(500);
        ?>

        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline">
                <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'ajax-post layui-btn','target-form'=>'target-form']) ?>
            </div>
        </div>
        <?php
        ActiveForm::end(); ?>

