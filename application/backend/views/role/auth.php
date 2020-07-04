<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
//use yii\widgets\ActiveForm;
$this->title = '角色授权';
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
layui.use('form', function() {
    var form = layui.form; 
    //监听表格复选框选择   
    form.on('checkbox(checkAll)', function (data) {  
        var child = $(data.elem).parent().parent().parent().find('input[type=\"checkbox\"]');  
        child.each(function(index, item){  
          item.checked = data.elem.checked;  
        });  
       form.render('checkbox'); 
    });
});
   

   
");
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);

$css = <<<CSS
    .table-child{padding-left: 30px;}
    .table-child2{padding-left: 80px;}
    .table-operator{padding-left: 120px;}
    .table-operator li{float: left;margin-right: 20px;}
    .auth-table{border-left: 1px solid #eee;border-top: 1px solid #eee;border-right: 1px solid #eee;}
    .auth-table .thead{padding:9px 15px;min-height: 20px;line-height: 20px;font-size: 14px;color: #666;background-color: #f8f8f8;border-bottom: 1px solid #eee;}
    .auth-table .rows{padding:9px 15px;min-height: 20px;line-height: 20px;font-size: 14px;color: #666;border-bottom: 1px solid #eee;}
CSS;

$this->registerCss($css);
?>


    <?php ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
    ]); ?>
    <div class="auth-table">


        <?php

        foreach($node_list as $node): //1
        $checkbox1 = in_array($node['url'], $authRules) ? true : false;
        $childHtml = '';
        if(isset($node['child'])):
                $childText = '';
                foreach ($node['child'] as $child):
                    $checkbox2 = in_array($child['url'], $authRules) ? true : false;
                    $childText2 = '';
                    if(!empty($child['child'])):
                        foreach ($child['child'] as $child2):
                            $childText3 = '';
                            $checkbox3 = in_array($child2['url'], $authRules) ? true : false;
                            //最后一项
                            if(!empty($child2['child'])):
                                $html = '';
                                foreach ($child2['child'] as $op):
                                    $checkbox = in_array($op['url'], $authRules) ? true : false;
                                    if($checkbox === true){
                                        $checkbox3 = true;
                                    }
                                    $html .= Html::checkbox('rules[]',$checkbox,['title'=>$op['name'],'value'=>$op['url'],'lay-skin'=>'primary']);
                                endforeach;
                                $text = Html::tag('div',$html,['class'=>'table-operator']);
                                $childText3 = Html::tag('div',$text,['class'=>'rows']);
                            endif;
                            //end 最后一项
                            if($checkbox3 === true){
                                $checkbox2 = true;
                            }
                            $text = Html::checkbox('rules[]',$checkbox3,['title'=>$child2['name'],'value'=>$child2['url'],'lay-skin'=>'primary','lay-filter'=>'checkAll']);
                            $text = Html::tag('div',$text,['class'=>'table-child2']);
                            $text = Html::tag('div',$text,['class'=>'rows']).$childText3;
                            $childText2 .= Html::tag('div',$text);
                        endforeach;
                    endif;
                    if($checkbox2 === true){
                        $checkbox1 = true;
                    }
                    $text = Html::checkbox('rules[]',$checkbox2,['title'=>$child['name'],'value'=>$child['url'],'lay-skin'=>'primary','lay-filter'=>'checkAll']);
                    $text = Html::tag('div',$text,['class'=>'table-child']);
                    $text = Html::tag('div',$text,['class'=>'rows']).$childText2;
                    $childText .= Html::tag('div',$text);
                endforeach;
            $childHtml = Html::tag('div',$childText,['class'=>'tbody']);
        endif;


        $thead = Html::tag('div',Html::checkbox('rules[]',$checkbox1,['title'=>$node['name'],'value'=>$node['url'],'lay-skin'=>'primary','lay-filter'=>'checkAll']));
        $html  = Html::tag('div',$thead,['class'=>'thead']).$childHtml;
        echo Html::tag('div',$html);

        endforeach;
        ?>
    </div>
    <?php
    $Button =  Html::submitButton('保存', ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);

    ActiveForm::end(); ?>


