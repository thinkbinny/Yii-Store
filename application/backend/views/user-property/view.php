<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */



$this->title = '订单明细';
$this->params['breadcrumbs'][] = ['label' => '资产记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layuimini-container">
    <div class="layuimini-main">

    <?
    echo DetailView::widget([
        'model' => $model,
        'options'=>['class'=>'layui-table'],
        'attributes' => [
            [
                'attribute'     =>  'id',
                'label'         =>  '编号ID',
                'captionOptions'=> ['style'=>'width:150px;']
            ],
            [
                'attribute' =>  'uid',
                'label'     =>  '用户UID'
            ],
            [

                'label'    => '用户昵称',
                'attribute' => 'uid',
                'options'   => ['width'=>180,],
                'value'=>function($model){
                    return \common\components\Func::get_nickname($model->uid);
                }
            ],
            [

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
        ],
    ]);
    ?>

    </div>
</div>
