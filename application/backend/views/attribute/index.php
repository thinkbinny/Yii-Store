<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$attribute   = new \backend\models\Attribute;
$this->title = '['.$attribute->get_model_by_id($searchModel->model_id).'] 属性列表(不含继承属性)';
$this->params['breadcrumbs'][] = ['label' => ' 模型管理', 'url' => ['model/index']];
$this->params['breadcrumbs'][] = '属性列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <?=$this->render('_tab_menu',['model'=>$searchModel]);?>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['id' => 'grid'],
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id',  'value' => 'id' ],
            //'id',
            /*[
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],*/
            'name',
            'title',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($data) {
                        return $data->get_attribute_type();
                    }
            ],
            [
                'attribute' => 'is_show',
                'format' => 'raw',
                'value' => function ($data) {
                        $is_show = $data->get_is_must();
                        return $is_show[$data->is_show];
                    }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<?php //echo Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-success gridview']);?>
</div>
<?php
$this->registerJs('
    $(".gridview").on("click", function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
    });
');
?>