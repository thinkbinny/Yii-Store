<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */



$this->title = '查看详细';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layuimini-container">
    <div class="layuimini-main">


    <fieldset class="layui-elem-field layui-field-title" >
        <legend >账户信息</legend>
    </fieldset>
    <?
    echo DetailView::widget([
        'model' => $user,
        'options'=>['class'=>'layui-table'],
        'attributes' => [
            [
                'attribute'     =>  'id',
                'label'         =>  '账户登陆ID',
                'captionOptions'=>['style'=>'width:150px;']
            ],
            [
                'attribute' =>  'username',
                'label'     =>  '账户名称'
            ],
            [
                'attribute' =>  'email',
                'label'     =>  '电子邮箱'
            ],
            [
                'attribute' =>  'mobile',
                'label'     =>  '手机号码'
            ],
        ],
    ]);
    ?>

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px">
            <legend >基础信息</legend>
        </fieldset>


    <?php
    echo DetailView::widget([
        'model' => $model,
        'options'=>['class'=>'layui-table'],
        'attributes' => [
            [

                'attribute'   =>  'uid',
                'captionOptions'=>['style'=>'width:150px;']
            ],

            [
                'format'    => 'raw',
                'attribute'   =>  'headimgurl',
                'value'=>function($data){
                    return Html::img($data->headimgurl,['style'=>'width:80px;']);
                }
            ],
            'nickname',
            [
                'attribute'   =>  'sex',
                'value'=>function($data)
                {
                    if($data->sex==1){
                        return '男';
                    }elseif($data->sex==2){
                        return '女';
                    }else{
                        return '未知';
                    }

                }
            ],

            //'birthday',

            'integral',
            'login',
            [
                'attribute'   =>  'reg_ip',
                'value'=>function($data)
                {
                    return long2ip($data->reg_ip);
                }
            ],
            [
                'attribute'   =>  'reg_time',
                'value'=>function($data)
                {
                    return date('Y年m月d日 H时m分s秒',$data->reg_time);
                }
            ],
            [
                'attribute'   =>  'last_login_ip',
                'value'=>function($data)
                {
                    return long2ip($data->last_login_ip);
                }
            ],
            [
                'attribute'   =>  'last_login_time',
                'value'=>function($data)
                {
                    return date('Y年m月d日 H时m分s秒',$data->last_login_time);
                }
            ],
            [
                'attribute'   =>  'status',
                'value'=>function($data)
                {
                    return $data->getStatusText();
                }
            ],

        ],
    ]);


    ?>
    </div>
</div>
