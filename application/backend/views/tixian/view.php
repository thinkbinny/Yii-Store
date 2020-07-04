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
                'label'         =>  '提现ID',
                'captionOptions'=> ['style'=>'width:150px;']
            ],

            [

                'label'    => '提现用户',
                'attribute' => 'uid',
                'options'   => ['width'=>180,],
                'value'=>function($model){
                    return \common\components\Func::get_nickname($model->uid) .'【'.$model->uid.'】';
                }
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'money',
            ],
            [

                'attribute' =>  'type',
                'options'   =>  ['width'=>110,],
                'value'     =>  function($model){
                    return $model->getTypeText();
                }
            ],

            [
                'header'      => '真实姓名',
                'attribute'   =>  'realname',
            ],
            [
                'header'      => '收款名称',
                'attribute'   =>  'open_account',
            ],
            [
                'header'      => '收款账号',
                'attribute'   =>  'account',
            ],
            [
                'visible'   =>  intval($model->status == -1 || $model->status == 1 || $model->status == 2?1:0 ),
                'header'=>'审核员',
                'attribute'   =>  'checker',
            ],
            [
                'visible'   =>  intval($model->status == -1 || $model->status == 1 || $model->status == 2?1:0 ),
                'header'=>'审核时间',
                'attribute'   =>  'checker_time',
                'format'    =>  ['date','Y-MM-dd H:i:s'],
            ],
            [
                'visible'   =>  intval($model->status == 2?1:0 ),
                'label'=>'财务人员',
                'attribute'   =>  'accountant',
            ],
            [
                'visible'   =>  intval($model->status == 2?1:0 ),
                'label'=>'打款时间',
                'attribute'   =>  'accountant_time',
                'format'    =>  ['date','Y-MM-dd H:i:s'],
            ],
            [
                'label'=>'提现状态',
                'attribute'   =>  'status',
                'value'    =>  function ($model){
                    return $model->getStatusText();
                },
            ],
            [
                'label'=>'申请时间',
                'options'=>['width'=>170,],
                'format'    =>  ['date','Y-MM-dd H:i:s'],
                'attribute'   =>  'created_at',

            ],
        ],
    ]);
    ?>

    </div>
</div>
