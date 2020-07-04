<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Nav;
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '物流公司编码表';
$this->params['breadcrumbs'][] = ['label' => '商城设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="layuimini-content-page">
    <div class="layuimini-container">
        <div class="layuimini-main">
            <?=$this->render('_tab_menu');?>
            <div style="margin-bottom:10px;" class="alert-danger alert fade in">
                友情提示：可使用Ctrl+F键 快速检索
            </div>
            <div class="layui-form" >
               <table class="layui-table">
                   <colgroup>
                       <col width="33%">
                       <col width="34%">
                       <col width="33%">
                   </colgroup>
                   <thead>
                       <tr>
                           <th>公司类型</th>
                           <th>公司名称</th>
                           <th>公司编码</th>
                       </tr>

                   </thead>
                   <tbody>
                        <?php
                        foreach ($volist as $vo):
                        ?>
                        <tr>
                            <td><?=$vo['2']?></td>
                            <td><?=$vo['0']?></td>
                            <td><?=$vo['1']?></td>

                        </tr>
                        <?php
                        endforeach;
                        ?>
                   </tbody>
               </table>
            </div>

        </div>
    </div>
</div>
