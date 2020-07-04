<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '字段列表';
$this->params['breadcrumbs'][] = ['label' => '模型管理', 'url' => ['model/index']];

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="layuimini-container">
    <div class="layuimini-main">
        <?=$this->render('_tab_menu',['model'=>$searchModel]);?>
    <?

echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sort',
                'label' => '排序',
                'format' => 'raw',
                'options'=>['width'=>60,],
                'value' => function($data) {
                    return Html::textInput('sort['.$data['id'].']', $data['sort'], ['class' => 'layui-input','style'=>'padding-left:0;height:25px;width:40px;text-align: center;']);
                }
            ],
            //'id',
            'field',
            [
                'options'=>['width'=>120,],
                'attribute'   =>  'name',
            ],
            [
                'options'=>['width'=>100,],
                'attribute'   =>  'formtype',
            ],
            [
              'options'=>['width'=>165,],
              'attribute'   =>  'created_at',
              'value'       =>  function($data){
                return date('Y-m-d H:i:s',$data->created_at);
              }
            ],
            [
                'options'=>['width'=>165,],
                'attribute'   =>  'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],
            [
                'contentOptions'=> ['style'=>'text-align: center'],
                'attribute' => 'isrequired',
                'format' => 'raw',
                'label' => '是否必填',
                'options'=>['width'=>90,],
                'value' => function($data) {
                    if($data['isrequired']==1){
                        return Html::button('是',['class'=>'layui-btn layui-btn-xs layui-btn-danger']);//'是';
                    }else{
                        return Html::button('否',['class'=>'layui-btn layui-btn-xs layui-btn-warm']);//'否';
                    }
                }
            ],
            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'attribute'   =>  'indexes',

            ],
            [
                'class' => 'backend\grid\SwitchColumn',
                'attribute' => 'status',
                'header' => '是否显示',
                'options'=>['width'=>90,],
            ],
            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>140,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => ' {update} {delete}',//字段管理
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '1000px', height: '90%', title: '更新字段',scrollbar:'Yes'}",
                    ]
                ],

            ],
        ],
    ]);


?>
    </div>
</div>
