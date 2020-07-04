<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '财产明细';
$this->params['breadcrumbs'][] = ['label' => '资产记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

");
$this->registerCss("

");
?>


<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>

    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?
echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {summary} {pager}',
        'pager'=>[
            //'options'=>['class'=>'layui-box layui-laypage layui-laypage-default'],
            'firstPageLabel'=>"第一页",
            'prevPageLabel'=>'上一页',//'Prev',
            'nextPageLabel'=>'下一页',//'Next',
            'lastPageLabel'=>'最后一页',
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'uid',
                'options'=>['width'=>49,],
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'uid',
            ],

            [
                //'attribute' => 'nickname',
                'format'    => 'raw',
                'header'=>'昵称',
                'options'=>['width'=>180,],
                'value'=>function($model){
                    return \common\components\Func::get_nickname($model->uid);
                }
            ],
            [
                'format'    =>  'raw',
                'attribute' =>  'scene',
                'options'   =>  ['width'=>110,],
                'value'     =>  function($model){
                    return $model->getSceneText();
                }
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'money_change',
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'money',
            ],
            [
                'header'=>'描述/说明',
                'attribute'   =>  'remarks',
            ],

            [
                'options'=>['width'=>170,],
                'attribute'   =>  'created_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],

             [
                 'class' => 'backend\grid\ActionColumn',
                 'options'=>['width'=>60,],
                 'header' => Yii::t('backend', 'Operate'),
                 'template' => '{view}',//字段管理 {update} {delete}
                 'buttons' => [


                    'view' => function ($url, $model, $key) {
                        $options = ['class'=>'layui-btn layui-btn-primary layui-btn-sm ajax-iframe-popup','style'=>'margin-left:0;','data-iframe'=>"{btn:false,scrollbar:'Yes',shadeClose:true,width: '800px', height: '400px', title: '财产明细'}"];
                        //$url = \yii\helpers\Url::to(['view','id'=>$model->uid]);
                        return Html::a('<span class="fa fa-eye"></span> 详细', $url, $options);

                    },


                 ],


            ],
        ],
    ]); ?>

</div>
</div>

