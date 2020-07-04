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
 
    form.on('radio(storage)', function(data){
      if(data.value == 'local'){
        $('#local').css('display','block');
        $('#qiniu').css('display','none');
        $('#aliyun').css('display','none');
        $('#qcloud').css('display','none');
      }else if(data.value == 'qiniu'){
        $('#local').css('display','none');
        $('#qiniu').css('display','block');
        $('#aliyun').css('display','none');
        $('#qcloud').css('display','none');
      }else if(data.value == 'aliyun'){
        $('#local').css('display','none');
        $('#qiniu').css('display','none');
        $('#aliyun').css('display','block');
        $('#qcloud').css('display','none');
      }else if(data.value == 'qcloud'){
        $('#local').css('display','none');
        $('#qiniu').css('display','none');
        $('#aliyun').css('display','none');
        $('#qcloud').css('display','block');
      } 
      
    });

 
});
");
?>
<style type="text/css">
    .watermark_pos td{border: 1px solid #e5e1dd; padding: 10px 22px;}
    .watermark_pos label{font-weight: 400;}

</style>

        <div class="layui-main">
        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form target-form'],
        ]);

        $storageData = [
            'local'     =>'本地 (不推荐)',
            'qiniu'     =>'七牛云存储',
            'aliyun'    =>'阿里云OSS',
            'qcloud'    =>'腾讯云COS',
        ];
        echo $form->field($model, 'data[storage]')->radioList($storageData,[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"storage\">";
                return $return;
            }
        ])->label('默认上传方式');


        ?>


            <!--本地-->
            <div id="local" style="<?=$model->data['storage']=='local' ? 'display: block': 'display: none'; ?>">
            <?php
            echo $form->field($model,'data[local][uploadfile]')
                ->textInput(['placeholder'=>'请填写附件上传路径'])
                ->label('附件上传路径')
                ->hint('<i class="fa fa-info-circle"></i> 默认“/uploads”')
                ->width(500);

            echo $form->field($model,'data[local][size]')
                ->textInput(['placeholder'=>'请填写允许上传附件大小'])
                ->label('允许上传附件大小')
                ->hint('<i class="fa fa-info-circle"></i> KB ，0代表不限制')
                ->width(500);

            echo $form->field($model,'data[local][exts]')
                ->textInput(['placeholder'=>'请填写允许上传附件类型'])
                ->label('允许上传附件类型')
                ->hint('<i class="fa fa-info-circle"></i> 多个用英文","隔开”')
                ->width(500);

            $watermark_enable = [
                    1 => '图片水印',
                    2 => '文字水印',
                    0 => '关闭水印',
            ];
            echo $form->field($model, 'data[local][watermark_enable]')->radioList($watermark_enable,[
                'item' => function($index, $label, $name, $checked, $value)
                {
                    $checked=$checked?"checked":"";
                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" >";
                    return $return;
                }
            ])->label('图片水印设置');

            ?>
            <div class="layui-form-item">
                <label class="layui-form-label" for="menu-name">图片水印位置</label>
                <div  class="layui-input-inline">
                    <table class="watermark_pos">
                        <?php
                        $watermark_pos = [
                            0 => '随机',
                            1 => '顶端居左',
                            2 => '顶端居中',
                            3 => '顶端居右',
                            4 => '中部居左',
                            5 => '中部居中',
                            6 => '中部居右',
                            7 => '底端居左',
                            8 => '底端居中',
                            9 => '底端居右',
                        ];

                        echo $form->field($model, 'data[local][watermark_pos]',['template' => '{input}'])
                            ->radioList($watermark_pos,[
                                'item' => function($index, $label, $name, $checked, $value)
                                {
                                    $checked=$checked?"checked":"";
                                    $return = '';
                                    if($value ==0 ){
                                        $return = "<tr><td colspan=\"3\" align=\"center\"><label><input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" ></label></td><tr>";
                                    }else{
                                        if($value % 3 == 1){
                                            $return .='<tr>';
                                        }
                                        $return = "<td><label><input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" ></label></td>";
                                        if($value % 3 == 0){
                                            $return .='</tr>';
                                        }
                                    }
                                    return $return;
                                }
                            ]);

                        ?>
                    </table>
                </div>
            </div>

            <?php

            echo $form->field($model,'data[local][watermark_text]')
                ->textInput(['placeholder'=>'请填写文字水印内容'])
                ->label('文字水印内容')
                //->hint('<i class="fa fa-info-circle"></i> 多个用英文","隔开”')
                ->width(500);


            if(empty($model->data['local']['watermark_text_path'])){
                $data = $model->data;
                $data['local']['watermark_text_path'] = '/assets/statics/fonts/watermark.ttf';
            }
            $path = '@webroot'.$model->data['local']['watermark_text_path'];
            if(!file_exists(Yii::getAlias('@webroot'.$model->data['local']['watermark_text_path']))){
                $hint = '<i class="fa fa-info-circle"></i> <font color="red">文字水印字体文件不存在,请上传</font>';
            }else{
                $hint = '';
            }
            echo $form->field($model,'data[local][watermark_text_path]')
                ->textInput(['placeholder'=>'请填写文字水印字体文件'])
                ->label('文字水印字体文件')
                ->width(500)
                ->hint($hint);

            if(empty($model->data['local']['watermark_images_path'])){
                $data = $model->data;
                $data['local']['watermark_images_path'] = '/assets/statics/images/watermark.png';
            }
            $path = '@webroot'.$model->data['local']['watermark_images_path'];
            if(!file_exists(Yii::getAlias('@webroot'.$model->data['local']['watermark_images_path']))){
                $hint = '<i class="fa fa-info-circle"></i> <font color="red">图片水印文件不存在,请上传</font>';
            }else{
                $hint = '';
            }
            echo $form->field($model,'data[local][watermark_images_path]')
                ->textInput(['placeholder'=>'请填写图片水印文件地址'])
                ->label('图片水印文件地址')
                ->width(500)
                ->hint($hint);

            ?>



            </div>
            <!--End 本地-->
            <!--七牛云存储-->
            <div id="qiniu" style="<?=$formParams['storage']=='qiniu' ? 'display: block': 'display: none'; ?>">
                <div class="layui-form-item">
                    <label class="layui-form-label">存储空间名称 <span class="tpl-form-line-small-title">Bucket</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qiniu][bucket]" value="<?=isset($formParams['qiniu']['bucket']) ? $formParams['qiniu']['bucket'] : '';?>"  type="text">
                    </div>
                    <!--<div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i>Bucket名称</div>-->
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">ACCESS_KEY <span class="tpl-form-line-small-title">AK</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qiniu][access_key]" value="<?=isset($formParams['qiniu']['access_key']) ? $formParams['qiniu']['access_key'] : '';?>"  type="text">
                    </div>
                    <!--<div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i>Bucket名称</div>-->
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">SECRET_KEY <span class="tpl-form-line-small-title">SK</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qiniu][secret_key]" value="<?=isset($formParams['qiniu']['secret_key']) ? $formParams['qiniu']['secret_key'] : '';?>"  type="text">
                    </div>
                    <!--<div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i>Bucket名称</div>-->
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">空间域名 <span class="tpl-form-line-small-title">Domain</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qiniu][domain]" value="<?=isset($formParams['qiniu']['domain']) ? $formParams['qiniu']['domain'] : 'http://';?>"  type="text">
                    </div>
                    <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 请补全http:// 或 https://，例如：http://static.cloud.com</div>
                </div>
            </div>
            <!--End 七牛云存储-->
            <!--阿里云OSS-->
            <div id="aliyun" style="<?=$formParams['storage']=='aliyun' ? 'display: block': 'display: none'; ?>">
                <div class="layui-form-item">
                    <label class="layui-form-label" >存储空间名称 <span class="tpl-form-line-small-title">Bucket</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[aliyun][bucket]" value="<?=isset($formParams['aliyun']['bucket']) ? $formParams['aliyun']['bucket'] : '';?>"  type="text">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >AccessKeyId</label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[aliyun][access_key_id]" value="<?=isset($formParams['aliyun']['access_key_id']) ? $formParams['aliyun']['access_key_id'] : '';?>"  type="text">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >AccessKeySecret</label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[aliyun][access_key_secret]" value="<?=isset($formParams['aliyun']['access_key_secret']) ? $formParams['aliyun']['access_key_secret'] : '';?>"  type="text">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >空间域名 <span class="tpl-form-line-small-title">Domain</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[aliyun][domain]" value="<?=isset($formParams['aliyun']['domain']) ? $formParams['aliyun']['domain'] : '';?>"  type="text">
                    </div>
                    <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 请补全http:// 或 https://，例如：http://static.cloud.com</div>
                </div>

            </div>
            <!--End 阿里云OSS-->
            <!--腾讯云COS-->
            <div id="qcloud" style="<?=$formParams['storage']=='qcloud' ? 'display: block': 'display: none'; ?>">
                <div class="layui-form-item">
                    <label class="layui-form-label" >存储空间名称 <span class="tpl-form-line-small-title">Bucket</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qcloud][bucket]" value="<?=isset($formParams['qcloud']['bucket']) ? $formParams['qcloud']['bucket'] : '';?>"  type="text">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >所属地域 <span class="tpl-form-line-small-title">Region</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qcloud][region]" value="<?=isset($formParams['qcloud']['region']) ? $formParams['qcloud']['region'] : '';?>"  type="text">
                    </div>
                    <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 请填写地域简称，例如：ap-beijing、ap-hongkong、eu-frankfurt</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >SecretId</label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qcloud][secret_id]" value="<?=isset($formParams['qcloud']['secret_id']) ? $formParams['qcloud']['secret_id'] : '';?>"  type="text">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >SecretKey</label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qcloud][bucket]" value="<?=isset($formParams['qcloud']['secret_key']) ? $formParams['qcloud']['secret_key'] : '';?>"  type="text">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" >空间域名 <span class="tpl-form-line-small-title">Domain</span></label>
                    <div  class="layui-input-inline">
                        <input class="layui-input" name="form[qcloud][domain]" value="<?=isset($formParams['qcloud']['domain']) ? $formParams['qcloud']['domain'] : '';?>"  type="text">
                    </div>
                    <div class="layui-form-mid layui-word-aux"><i class="fa fa-info-circle"></i> 请补全http:// 或 https://，例如：http://static.cloud.com</div>
                </div>


            </div>
            <!--End 腾讯云COS-->
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'ajax-post layui-btn','target-form'=>'target-form']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>

    </div>
