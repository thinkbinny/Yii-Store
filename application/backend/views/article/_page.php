<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\bootstrap\Nav;
$this->title = '详细内容';
$this->params['breadcrumbs'][] = ['label' => ' 内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
.layui-body,.layui-layout-admin .layui-footer{left:180px;}
.ui-admin .layui-body-big{left:50px;}
.layui-breadcrumb{padding-left: 190px;}


.sidebar2 {width: 180px;position: fixed;top: 50px;bottom: 45px; z-index: 102; background-color: #eaedf1;transition: width .3s,left .3s;font-size: 12px;}
.sidebar2 .sidebar_title{line-height: 40px;height: 40px;padding-left:30px;font-size: 16px;border-bottom:1px solid #ddd}
.sidebar2 .article_nav_list{overflow: auto;height: 100%;}
.sidebar2 .article_nav_list li {line-height: 35px;}
.sidebar2 .article_nav_list li a{display: block;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;padding-left: 30px;font-size: 13px;}
.sidebar2 .article_nav_list li a:hover, .article_nav_list li a.active{background-color: #fff;}

");
$this->registerJs("
    $('.alert-success').css('margin-left',190);
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    //监听表格复选框选择
    //全选
        form.on('checkbox(check-all)', function (data) {
            var child = $(data.elem).parents('table').find('tbody input[type=\"checkbox\"]');  
            child.each(function(index, item){  
              item.checked = data.elem.checked;  
            });  
            form.render('checkbox'); 
        });
    });
   
");

?>


<div class="layui-main" style="margin-top: 0;" >
    <div class="sidebar2" >
        <div class="sidebar_title">栏目列表</div>
        <ul class="article_nav_list">
            <?
            $category           = new \backend\models\Category();
            $GetCategoryList    = $category->GetCategoryList();
            $html = '';
            $category_id        = Yii::$app->request->get('category_id');
            foreach ($GetCategoryList as $val):
                $active = '';
                if($category_id == $val['id']){
                    $active = 'active';
                }

                $a      = Html::a($val['title'],['index','category_id'=>$val['id']],['class'=>$active]);
                $html  .= Html::tag('li',$a);
            endforeach;
            echo $html;
            ?>

        </ul>
    </div>

    <div class="" style="padding-left:190px;width: auto">
        <?php

        $this->registerJs("
             highlight_subnav('".Url::to(['article/index'])."');
            ");
        $category_id = Yii::$app->request->get('category_id',0);
        $data = array();
        $data[]=['label'=>'详细内容','url'=>'','options'=>['class' => 'active']];
        echo Nav::widget([
            'items' => $data,
            'options' => ['class' => 'nav-tabs'],
        ]);

        ?>
        <div class="" style="width: auto;padding-top: 10px; ">
            <?php
            $form = ActiveForm::begin([
                'options'=>['class'=>'layui-form'],
                'fieldClass'        =>'backend\widgets\ActiveField',
            ]);
            $model->id = $model->isNewRecord ? Yii::$app->request->get('category_id') :$model->id;
            echo $form->field($model ,'id',['options'=>['style'=>'display: none']])->hiddenInput();
            echo $this->render('_field',['form'=>$form,'field'=>$field,'model'=>$model]);
            $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn']);
            echo Html::tag('div',$Button,['class'=>'layui-form-button']);
            ActiveForm::end();
            ?>
        </div>
    </div>

</div>

