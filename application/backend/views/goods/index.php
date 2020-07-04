<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品列表';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$css = <<<CSS

.goods-detail{padding:9px 5px !important;}
.goods-detail > div{float: left;}
.goods-detail .goods-image{margin-right: 6px;}
.goods-detail .goods-image .pic_url {width: 72px;height: 72px;background: no-repeat center center / 100%; }
.goods-detail .goods-info{width: 180px;height: 72px;}
.goods-detail .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.goods-detail .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.goods-detail .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }

CSS;
$this->registerCss($css);
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
        'columns' => [
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                "contentOptions" => ['style' => 'text-align:center;'],
                'options'=>['width'=>49,],

            ],

            [
                'options'=>['width'=>80,],
                'attribute' => 'id',
                'format' => 'raw',

            ],
            [
                'options'   =>['width'=>280,],
                'attribute' => 'title',
                'format'    => 'raw',
                'contentOptions'   => ['class'=>'goods-detail'],
                'value'     => function($model){
                    $pic_url = $model -> getImageUrl();
                    $img  = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('{$pic_url}')"]);
                    $info = Html::tag('p',$model->title,['class'=>'goods-title']);
                    $html = Html::tag('div',$img,['class'=>'goods-image']);
                    $html .= Html::tag('div',$info,['class'=>'goods-info']);
                    return $html;
                }
            ],
            //'pid',
            //'title',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'label' => '商品分类',
                //'options'=>['width'=>120,],
                'value'=>function ($model){
                    return $model->getCategoryIdText();
                }
            ],
            [
                'attribute' => 'price',
                'format' => 'raw',
                'options'=>['width'=>80,],
                'label'=>'出售价'
            ],
            [
                'attribute' => 'sales_actual',
                'format' => 'raw',
                'options'=>['width'=>70,],
                'label'=>'销量'
            ],
            [
                'attribute' => 'stock',
                'format' => 'raw',
                'options'=>['width'=>70,],
                'label'=>'库存'
            ],
            [
                'attribute' => 'sort',
                'format' => 'raw',
                'label'=>'排序',
                'options'=>['width'=>60,],
            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'status',
                'format' => 'raw',
                "contentOptions" => ['style' => 'text-align:center;'],
                'value' => function($model){
                    return $model->getStatusText();
                },

            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'updated_at',
                'format' => ['date','Y-MM-dd H:i:s'],
                'label' => '更新时间',
            ],



            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>135,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',/*{copy}   {move} {merge} */
                'buttons' => [
                    'update' =>function($url, $model, $key){

                        if($model->prom_type  == 1 ){
                            //$prom_id = $model->prom_id;
                            $prom_id = $model -> getPromSeckillStatus();
                            //开始不可以修改
                            if( $prom_id==true ){
                                $options = [
                                    'title' => Yii::t('yii', 'Update'),
                                    'class' => 'btn btn-primary btn-xs layui-btn-disabled',
                                ];
                                return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), "javascript:popup.alert('抢购活动已启无法修改');", $options);
                            }else{
                                $options = [
                                    'title' => Yii::t('yii', 'Update'),
                                    'class' => 'btn btn-primary btn-xs ajax-iframe-popup',
                                    'data-iframe'   => "{width: '1100px', height: '98%', title: '更新商品',scrollbar:'Yse'}",
                                ];
                                return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
                            }


                        }else{
                            $options = [
                                'title' => Yii::t('yii', 'Update'),
                                'class' => 'btn btn-primary btn-xs ajax-iframe-popup',
                                'data-iframe'   => "{width: '1100px', height: '98%', title: '更新商品',scrollbar:'Yse'}",
                            ];
                            return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
                        }
                    },
                    'copy'=> function ($url, $model, $key) {
                        $options = [
                            'class' => 'btn btn-success btn-xs',
                        ];
                        return Html::a('<span class="far fa-copy"></span> 一键复制', $url, $options);
                    }
                ],
                /*'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '1100px', height: '98%', title: '更新商品',scrollbar:'Yse'}",
                    ]
                ],*/
            ],
        ],
    ]);
    /*}catch(\Exception $e){
        // todo
    }*/
    ?>
    </div>
</div>
