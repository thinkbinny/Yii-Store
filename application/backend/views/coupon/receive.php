<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '优惠券领取记录';
$this->params['breadcrumbs'][] = ['label' => '营销管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>





<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
        <div class="page-toolbar clearfix">
            <div class="layui-btn-group ">


            </div>
            <div class="page-filter pull-right layui-search-form">
                <?php

                $form = ActiveForm::begin([
                    'fieldClass' => 'backend\widgets\ActiveSearch',
                    'action' => ['index'],
                    'method' => 'get',
                    'options'=>['class'=>'layui-form'],
                ]);

                echo $form->field($searchModel, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
                    ->dropDownList($searchModel->getStatus(),['prompt' => '优惠券状态'])->label(false);

                echo $form->field($searchModel, 'rangedate',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
                    ->textInput(['placeholder' => '请选择领取时间'])->label(false);
                $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
                echo Html::submitButton($text,['class'=>'layui-btn']);
                ActiveForm::end();


                ?>

            </div>
        </div>
    <?

    try {
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'columns' => [
            [
                'options'=>['width'=>80,],
                'attribute' => 'id',
                'format' => 'raw',
                'label'=>'ID',
            ],
            [
                'options'=>['width'=>170,],
                'attribute' => 'uid',
                'format' => 'raw',
                'label'=>'领取用户',
                'value'=>function($model){

                }

            ],


            'name',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'options'=>['width'=>80,],
                'label'=>'类型',
                'value'=>function($model){
                    return $model->getTypeText();
                }
            ],
            [
                'attribute' => 'min_price',
                'format' => 'raw',
                'options'=>['width'=>120,],
            ],

            [
                'header'=>'优惠方式',
                'format' => 'raw',
                'options'=>['width'=>120,],
                'value'=>function($model){
                    if($model->type == 1){
                        return '立减 '.$model->price.' 元';
                    }else{
                        return '打 '.$model->discount.' 折';
                    }
                }
            ],
            [
                'header'=>'有效期',
                'format' => 'raw',
                'options'=>['width'=>150,],
                'value'=>function($model){
                    if($model->expire_type == 1){
                        return '领取 '.$model->expire_day.' 天内有效';
                    }else{
                        return date('Y-m-d',$model->start_time).'~'.date('Y-m-d',$model->end_time);
                    }
                }
            ],



            [
                'options'=>['width'=>155,],
                'attribute' => 'created_at',
                'label'=>'领取时间',
                'format' => ['date','Y-m-d H:i:s'],
            ],


        ],
    ]);
    }catch(\Exception $e){
        // todo
    }
    ?>
    </div>
</div>
