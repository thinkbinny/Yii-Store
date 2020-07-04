<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '查看详细';
$this->params['breadcrumbs'][] = ['label' => '模板管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .view-mian{
        padding: 20px;
        line-height: 22px;
        background-color: #393D49;
        color: #fff;
        font-weight: 300;
        width: 100%;
        height: 380px;
    }
    .view-mian textarea{
        display: block;
        width: 100%;
        height: 350px;
        border: 5px solid #F8F8F8;
        border-top-width: 5px;
        /*border-top-width: 0;*/
        padding: 10px;
        line-height: 20px;
        overflow: auto;
        background-color: #3F3F3F;
        color: #eee;
        font-size: 14px;
        font-family: Courier New;
    }
</style>
<div class="view-mian" style="padding-top: 15px;">
        <textarea name=""><?=$model->remark?></textarea>


    <?

    /*echo DetailView::widget([
        'model' => $model,
        'options'=>['class'=>'layui-table'],
        'attributes' => [

            [
                'captionOptions'=>['style'=>'width:150px;'],
                'attribute' =>  'remark',
                'label'     =>  '使用方法'
            ],

        ],
    ]);*/
    ?>
</div>
