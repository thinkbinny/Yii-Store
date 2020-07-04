<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '积分管理';
$this->params['breadcrumbs'][] = ['label' => '营销管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>





<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>
    <?
    //try {
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'rowOptions'=>function($model,$key, $index){
            if($model->status == 2){
                return ['style'=>'color: #ddd;'];
            }elseif($model->status == 0){
                return ['style'=>'color: #ddd;'];
            }
        },
        'columns' => [

            [
                'options'=>['width'=>80,],
                'attribute' => 'id',
                'format' => 'raw',
                'label'=>'ID',
            ],

            [
                'options'=>['width'=>150,],
                'attribute' => 'uid',
                'format' => 'raw',
                'label'=>'用户昵称',
                'value' => function($model){
                    return \common\components\Func::getMemberInfo($model->uid,'nickname')."【UID:{$model->uid}】";
                }
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'options'=>['width'=>90,],
                'value'=>function($model){
                    return  $model->getTypeText();
                }
            ],


            [
                'attribute' => 'amount',
                'format' => 'raw',
                'label'=>'变动数量',
                'options'=>['width'=>90,],
            ],
            [
                'attribute' => 'total',
                'format'    => 'raw',
                'label'     => '当前余额',
                'options'   =>['width'=>90,],
            ],

            [

                //'options'=>['width'=>150,],
                'header' => '描述/说明',
                'format'    => 'raw',
                'attribute' => 'description',
            ],
            [
                //'options'=>['width'=>200,],
                'header' => '管理员备注',
                'format'    => 'raw',
                'attribute' => 'remark',
            ],
           /* [
                'options'=>['width'=>165,],

                'format' => ['date','Y-MM-d H:i:s'],
                'attribute' => 'extime_time',
            ],*/
            [
                'options'=>['width'=>165,],
                'attribute' => 'created_at',
                'format' => ['date','Y-MM-d H:i:s'],
            ],
            /*[
                'options'=>['width'=>165,],
                'attribute' => 'status',
                'format'    => 'raw',
                'value'=>function($model){
                    return $model->getStatusText();
                }
            ]*/
            //Yii::$app->formatter->asRelativeTime($model->created_at);

        ],
    ]);
    /*}catch(\Exception $e){
        // todo
    }*/
    ?>
    </div>
</div>
