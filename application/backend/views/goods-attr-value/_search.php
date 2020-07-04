<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
 $('.layui-icon-add-circle-fine').selectWindows({
    title:'添加属性值',
    skin:'',
    area:['750px','380px'],
    done:function(ret) {
      window.location.reload();
    }
 });
JS;

$this->registerJs($js);

?>
<div class="page-toolbar">
    <div class="layui-btn-group">

        <?php
        echo Html::a('&nbsp;添加','javascript:;',[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine',
            'data-attribute'        =>'attr_id',
            'data-attribute-value'  =>$model->attr_id,
            'data-url'      => Url::to(['create'])
        ]);

        /*echo Html::a('&nbsp;启用','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
        ]);
        echo Html::a('&nbsp;禁用','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '0',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
        ]);
         echo Html::a('&nbsp;删除','javascript:;',[
             'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
             'data-url'      => Url::to(['delete']),
             'data-form'     => 'ids',
         ]);*/

        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">
        <?php
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        $placeholder = '请输入属性名称' ;
        echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->getStatus(),['prompt' => '请选择'])->label(false);

        echo $form->field($model, 'value',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
            ->textInput(['placeholder' => $placeholder])->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

