<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '系统日志';
//$this->params['breadcrumbs'][] = ['label' => '日志管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
    .view-mian{
        padding: 0px;
        line-height: 22px;
        background-color: #393D49;
        color: #fff;
        font-weight: 300;
        width: 100%;
        /*height: 380px;*/
    }
    .view-mian textarea{
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
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
CSS;

$this->registerCss($css);

?>
<div class="view-mian" >
  <textarea name="">提交地址：<?=Yii::$app->urlManager->hostInfo.$model->action_url?>&nbsp;
提交参数：
      <?php //JSON_PRETTY_PRINT
      $attr = json_decode($model->action_log);
      //print_r($attr);
      echo json_encode($attr,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT ); //  JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
      ?>
  </textarea>
</div>

