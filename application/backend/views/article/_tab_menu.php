<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;

$category_id = Yii::$app->request->get('category_id',0);
$data = array();

if(empty($category_id)){
    $data[]=['label'=>'全部列表','url'=>['article/index']];
}else{
    $category           = new \backend\models\Category();
    $info = $category->GetCategoryInfo($category_id);
    $data[]=['label'=>"【{$info['title']}】列表",'url'=>['article/index','category_id'=>$category_id]];
    $data[]=['label'=>'添加内容','url'=>['article/create','category_id'=>$category_id]];
}
echo Nav::widget([
    'items' => $data,
    'options' => ['class' => 'nav-tabs'],
]);