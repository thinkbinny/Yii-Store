<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '素材管理';
$this->params['breadcrumbs'][] = ['label' => '微信设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="layuimini-container">
    <div class="layuimini-main">

    <?=$this->render('_tab_menu');?>
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
                'options'=>['width'=>400,],
                'attribute' => 'media_id',
                'format' => 'raw',

            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value'=>function($data){
                $html = $data->name;
                if($data->msg_type==2){
                    $content = json_decode($data->content,true);
                    $url    = $content['url'];
                    $html   = Html::a($html,$url,['target'=>'_blank','style'=>'color:#009688']);
                }/*elseif($data->type==2){
                    $html   =Html::a($html,$data->content,['target'=>'_blank','style'=>'color:#009688']);
                }*/
                return $html; //Html::button('复制URL',['class'=>'btn btn-success btn-xs']).' '.
                }
            ],

            [
                'options'=>['width'=>130,],
                'attribute' => 'msg_type',
                'format' => 'raw',
                'value'  => function($data){
                    return $data->getMsgTypeText($data->msg_type);
                }
            ],
            [
                'options'=>['width'=>180,],
                'attribute' => 'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],


            /*[
                'options'=>['width'=>80,],
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update}',// {delete}
                'buttons' => [

                ],
            ],*/
        ],
    ]);
    ?>
    </div>
</div>
