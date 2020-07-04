<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提现申请';
$this->params['breadcrumbs'][] = ['label' => '财务管理', 'url' => ['index']];
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


            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'options'       =>['width'=>70,],
                'attribute'     =>  'id',
            ],

            [
                'attribute' => 'uid',
                'format'    => 'raw',
                'header'=>'用户昵称',
                //'options'=>['width'=>180,],
                'value'=>function($model){
                    return \common\components\Func::get_nickname($model->uid).'【ID:'.$model->uid.'】';
                }
            ],
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'options'=>['width'=>90,],
                'attribute'   =>  'money',
            ],
            /*[
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'format'    =>  'raw',
                'attribute' =>  'type',
                'options'   =>  ['width'=>90,],
                'value'     =>  function($model){
                    return $model->getTypeText();
                }
            ],*/
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                //'header'        =>  '真',
                'attribute'     =>  'realname',
                'options'   =>  ['width'=>100],

            ],
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'header'        =>  '收款名称',
                'attribute'     =>  'open_account',
                'options'   =>  ['width'=>100],

            ],
            [
                'header'        =>  '收款账号',
                'attribute'     =>  'account',
                'options'   =>  ['width'=>220],

            ],

            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'options'=>['width'=>155,],
                'attribute'   =>  'created_at',
                'label'       =>  '申请时间',
                'format'    =>  ['date','Y-MM-dd H:i:s'],

            ],
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'header'        =>  '提现状态',
                'attribute'     =>  'status',
                'options'   =>  ['width'=>90],
                'value'     => function ($model){
                    return $model->getStatusText();
                }
            ],
             [
                 'class' => 'backend\grid\ActionColumn',
                 'options'=>['width'=>165,],
                 'header' => Yii::t('backend', 'Operate'),
                 'template' => '{view} {checker}',//字段管理 {update} {delete}
                 'buttons' => [


                    'view' => function ($url, $model, $key) {
                        if($model->status == 0){
                            $url = Url::to(['checker','id'=>$model->id,'opt'=>'adopt']);
                            $options = ['class'=>'layui-btn layui-btn-normal layui-btn-xs ajax-get confirm','data-alert'=>'确定审核通过吗?','style'=>'margin-left:0;',];
                            return Html::a('<span class="fa fa-check"></span> 审核通过', $url, $options);
                        }else{
                            $options = ['class'=>'layui-btn layui-btn-primary layui-btn-xs ajax-iframe-popup','style'=>'margin-left:0;','data-iframe'=>"{btn:false,scrollbar:'Yes',shadeClose:true,width: '800px', height: '510px', title: '查看明细'}"];
                            return Html::a('<span class="fa fa-eye"></span>查看详细', $url, $options);
                        }

                    },
                     'checker' => function ($url, $model, $key) {
                        if($model->status == 0) {
                            $url = Url::to(['checker','id'=>$model->id,'opt'=>'fail']);
                            $options = ['class'=>'layui-btn layui-btn-danger layui-btn-xs ajax-get confirm','data-alert'=>'确定审核不通过吗?','style'=>'margin-left:0;'];

                            return Html::a(' <span class="fa fa-times"></span> 不通过', $url, $options);
                        }elseif($model->status == 1){

                             $options = ['class'=>'layui-btn layui-btn-xs ajax-get confirm','data-alert'=>'确定已经打款了吗？','style'=>'margin-left:0;'];
                             $url = Url::to(['accountant','id'=>$model->id]);
                             return Html::a('<span class="fa fa-check"></span> 已打款', $url, $options);
                        }else{
                            return '';
                        }

                     },

                 ],


            ],
        ],
    ]); ?>

</div>
</div>

