<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '自动回复';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['wx/config']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

   
");
?>
<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>

<div class="layui-main" style="width:auto; ">
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?php ActiveForm::begin(); ?>
    <?= GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',//{summary}
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'options'=>['width'=>150,],
            ],
            [
                'attribute' => 'msg_type',
                'format' => 'raw',
                'options'=>['width'=>150,],
                'value' => function($data) {
                    return $data->getMsgTypeText($data->msg_type);
                }
            ],
            [
                //'options'=>['width'=>300,],
                'attribute' => 'type',
                'label' => '关键字',
                'format' => 'raw',
                'value'  => function($data){
                    return $data->getKeywordText($data->id);
                }
            ],


            [
                'options'=>['width'=>170,],
                'attribute' => 'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],

            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],
            [
                'options'=>['width'=>150,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',// {delete}
                'buttons' => [

                ],
                'buttonOptions'=>[
                      'update'=>[
                          'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                          'data-iframe'   => "{width: '900px', height: '400px', title: '更新规则'}",
                      ]
                ]
            ],
        ],
    ]);
    //echo Html::submitButton('排序', ['class' => 'layui-btn']);
    ActiveForm::end(); ?>
</div>
    </div>
</div>
