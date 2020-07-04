<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模板消息';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['wx/config']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
$('.btn-info').click(function(){
    var Url = $(this).attr('href');
    
    
    layer.open({
       title:false, 
       closeBtn: false,
       shadeClose:true,
       btn: ['关闭'],
        area:  ['750px', '435px'],
        shade: 0.8, 
        btnAlign: 'c',
        moveType: 1,
        type: 2, 
        content: [Url], //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
    return false;
})
 
   
");
?>
<div class="layuimini-container">
    <div class="layuimini-main">
<div id="w3-error-0" style="line-height: 22px;" class="alert-danger alert fade in">

    温馨提示：<br>
    消息提醒，即微信模板消息，需要先登录微信公众号平台，添加插件，申请开通模板消息。<br>
    然后选择填写所在行业： <span style="color: #00aaef">IT科技/互联网|电子商务</span>，如果有其他行业则选填：<span style="color: #00aaef">IT科技/电子技术</span>。每月可更改1次所选行业<br>
    启用列表所需要的模板消息，即可在相应事件触发模板消息；编辑模板消息备注，可增加显示自定义提示消息内容<br>
    每个公众号账号可以同时使用25个模板，超过将无法使用模板消息功能。<br>
    如果在使用中发现使用模板超出了25个，但这里并没有使用这么多，可能是微信后台本来就已有其他的模板，请前往自行删除
</div>

    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?= GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',//{summary}
        'columns' => [


            [
                'options'=>['width'=>200,],
                'attribute' => 'title',
                'format' => 'raw',

            ],
            [
                //'options'=>['width'=>200,],
                'attribute' => 'template_sn',
                'format' => 'raw',

            ],

            [
                'options'=>['width'=>180,],
                'attribute' => 'created_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],
            [
                'options'=>['width'=>180,],
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
                'template' => '{view} {update}',// {delete}
                'buttons' => [
                    'view'=>function($url, $model){
                        //return '演示';
                        return Html::a('<span class="fa fa-eye"></span> 演示', $url, ['class'=>'btn btn-info btn-xs']);
                    }
                ],
                'buttonOptions'=>[
                        'update'=>[
                            'class'=> 'btn btn-primary btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '900px', height: '450px', title: '更新模板'}",
                        ]
                ]
            ],
        ],
    ]);
    ?>

  </div>
</div>
