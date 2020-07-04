<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '属性值列表';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
     $('.update-windows').selectWindows({
    title:'更新属性值',
    skin:'',
    area:['500px','260px'],
    done:function(ret) {
      window.location.reload();
    }
 });
JS;

$this->registerJs($js);
?>

<div class="layuimini-container">
    <div class="layuimini-main">

    <?=$this->render('_search',['model'=>$searchModel]);?>


    <?
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',//{summary}
        'columns' => [
            [
                //'label'     => 'ID',
                'attribute' => 'id',
                "contentOptions" => ['style' => 'text-align:center;'],
                "headerOptions" => ['style' => 'text-align:center;'],
                'options'=>['width'=>90,],
            ],

            [
                'attribute' => 'value',
                'format' => 'raw',

            ],


            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],

            [
                'options'=>['width'=>170,],
                'attribute' => 'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],

            [
                'options'=>['width'=>145,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',// {delete}
                'buttons' => [
                    'update'=>function($url,$model){
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-url' => $url,
                            'class' => 'btn btn-primary btn-xs update-windows',
                        ];

                        return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), 'javascript:;', $options);

                    }
                ],

            ],
        ],
    ]);
   ?>
    </div>
</div>
