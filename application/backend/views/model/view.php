<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' 模型管理';
$this->params['breadcrumbs'][] = ['label' => ' 内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('_tab_model_field_menu');?>
<div class="layui-main">

    <?
ActiveForm::begin(['enableClientScript'=>false]);
echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sort',
                'label' => '排序',
                'format' => 'raw',
                'options'=>['width'=>120,],
                'value' => function($data) {
                    return Html::textInput('sort['.$data['id'].']', $data['sort'], ['class' => 'layui-input']);
                }
            ],
            //'id',
            'field',
            'name',
            'formtype',
            [
              'options'=>['width'=>180,],
              'attribute'   =>  'created_at',
              'value'       =>  function($data){
                return date('Y-m-d H:i:s',$data->created_at);
              }
            ],
            [
                'options'=>['width'=>180,],
                'attribute'   =>  'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => '是否启用',
                'options'=>['width'=>90,],
                'value' => function($data) {
                    return DisplayStyle::widget(['id'=>$data['id'],'defaultValue'=>$data['status']]);
                }
            ],
            [
                'class' => 'common\grid\ActionColumn',
                'options'=>['width'=>250,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{view} {update} {delete}',//字段管理
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = ['class'=>'btn btn-info btn-xs'];
                        return Html::a('<span class="fa fa-eye"></span> 字段管理', $url, $options);
                        /*return Html::a('<span class="fa fa-plus"></span> 添加子菜单', ['create', 'pid' => $key], [
                            'title' => '添加子菜单',
                            'class' => 'btn btn-success btn-xs'
                        ]);*/
                    },
                ],

            ],
        ],
    ]); ?>

<?php
echo Html::submitButton('排序', ['class' => 'btn btn-info']);
ActiveForm::end();
?>
</div>
