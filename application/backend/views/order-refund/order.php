<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退货退款';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = ['label' => '售后管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS

CSS;
$this->registerCss($css);

?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table order-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',//{summary}
        'columns' => [
            [
                'header'    => '订单号',
                'format'    => 'raw',
                'options'   => ['width'=>350],
                'attribute' => 'order_sn',
            ],
            [

                'format'    => 'raw',
                'options'   => ['width'=>120],
                'attribute' => 'type',
                'value'     => function ($model){
                    return $model->getTypeText();
                }
            ],

            [
                'header'    =>'付款金额',
                'format'    => 'raw',
                'attribute' => 'pay_money',
                'options'   =>['width'=>110,],
                'value'     => function($model){
                    return '￥'.$model->pay_money;
                }
            ],

            [
                'header'    =>'退回金额',
                'format'    => 'raw',
                'attribute' => 'refund_money',
                'options'   =>['width'=>110,],
                'value'     => function($model){
                    return '￥'.$model->refund_money;
                }
            ],


            [
                'header'=>'处理状态',
                'format' => 'raw',
                'options'=>['width'=>110,],
                "value"=>function($model){
                    return $model->getStatusText();
                }
            ],
            [
                'header'    => '创建时间',
                'format'    => ['date','Y-MM-dd H:i:s'],
                'attribute' => 'created_at',
                'options'   => ['width'=>145,],

            ],

            [
                'options'   =>['width'=>150,],
                'class'     => 'backend\grid\ActionColumn',
                'header'    => Yii::t('backend', 'Operate'),
                'template' => '{orderview} {view}',// {delete}
                'buttons'   => [

                    'orderview'=>function($url,$model){
                        $url = Url::to(['order/view','id'=>$model->order_id]);
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'class' => 'btn btn-info btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '1100px', height: '90%', title: '订单详细',scrollbar:'Yes',btn:false,shadeClose:true}",
                        ];
                        return Html::a('<span class="fa fa-eye"></span> 订单详细', $url, $options);
                    },
                    'view' => function($url,$model){

                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '900px', height: '90%', title: '退款/退货退款/换货',scrollbar:'Yes',shadeClose:true}",
                        ];
                        return Html::a('<span class="fa fa-eye"></span> 查看', $url, $options);
                    }
                ],

            ],
        ],
    ]);
   ?>
    </div>
</div>
