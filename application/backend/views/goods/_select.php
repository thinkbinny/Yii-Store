<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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
.goods-detail .goods-info{width: 380px;height: 72px;}
.goods-detail .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.goods-detail .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.goods-detail .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }

.layui-table .layui-form-radio{margin:0;padding-right:0;}
.layui-table .layui-form-radio > i{margin-right:0;}
CSS;
$this->registerCss($css);
$js = <<<JS
var windowIndex = parent.layer.getFrameIndex(window.name);
function mySubmit(option){ 
  
    //console.log(option);
    var data = $('.layui-table').find("input[type='radio']:checked");
    var id   = data.val();
    if(id === undefined){
        layer.msg('请选择商品');
        return false;
    }
    var info = {
        goodsId:id,
        title:data.data('title'),
        imageUrl:data.data('image-url'),
        skuUrl:data.data('sku-url')
    }
    option.done(info);
    parent.layer.close(windowIndex);
    return false;
}
JS;

$this->registerJs($js,\yii\web\View::POS_END);

?>

<div class="layuimini-container">
    <div class="layuimini-main">

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
                echo $form->field($searchModel, 'category_id',['options'=>['class'=>'layui-input-inline','style'=>'width: 180px;']])
                    ->dropDownList($searchModel->getCategoryId(),['encode' => false,'prompt' => '商品分类'])->label(false);

                echo $form->field($searchModel, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
                    ->dropDownList($searchModel->getStatus(),['prompt' => '商品状态'])->label(false);

                echo $form->field($searchModel, 'value',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
                    ->textInput(['placeholder' => '请输入商品标题'])->label(false);
                $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
                echo Html::submitButton($text,['class'=>'layui-btn']);
                ActiveForm::end();
                ?>

            </div>
        </div>

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
                //'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'format'    => 'raw',
                "contentOptions" => ['style' => 'text-align:center;'],
                "headerOptions" => ['style' => 'text-align:center;'],
                'options'   => ['width'=>60,],
                'header'    => '选择',
                'value'     => function ($model){
                    $options = [
                        'value'     => $model->id,
                        'data-title'=> $model->title,
                        'data-image-url'=> $model->getImageUrl(),
                        'data-sku-url'=>Url::to(['prom-seckill/sku','goods_id'=>$model->id])
                    ];
                    if($model->prom_type == 1){
                        $options['disabled'] = true;
                    }
                    return Html::radio('id',false,$options);
                }

            ],

            [
                'options'=>['width'=>110,],
                'attribute' => 'id',
                'format' => 'raw',

            ],
            [
                //'options'   =>['width'=>280,],
                'attribute' => 'title',
                'format'    => 'raw',
                'contentOptions'   => ['class'=>'goods-detail'],
                'value'     => function($model){
                    $pic_url = $model -> getImageUrl();
                    $img  = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('{$pic_url}')"]);
                    $info = Html::tag('p',$model->title,['class'=>'goods-title']);
                    if(!empty($model->prom_type)){
                        $info .= Html::tag('p','<span class="layui-btn layui-btn-xs layui-btn-danger">'.$model->getPromTypeText().'</span>');
                    }
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
                'options'=>['width'=>150,],
                'value'=>function ($model){
                    return $model->getCategoryIdText();
                }
            ],
            [
                'attribute' => 'price',
                'format' => 'raw',
                'options'=>['width'=>90,],
                'label'=>'出售价'
            ],

            [
                'attribute' => 'stock',
                'format' => 'raw',
                'options'=>['width'=>90,],
                'label'=>'库存'
            ],

        ],
    ]);
    /*}catch(\Exception $e){
        // todo
    }*/
    ?>
    </div>
</div>
