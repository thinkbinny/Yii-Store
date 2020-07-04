<?php
namespace backend\grid;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
class SortColumn extends \yii\grid\ActionColumn {
    /**
     * {@inheritdoc}
     */
    public $header          = '排序';
    public $attribute       = 'sort';
    public $classOptions    = array();
    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {

        $url       =  Url::to(['sort']);
        $attribute =  $this->attribute;
        $options = array_merge([
            'class'     => 'layui-input ajax-sort-submit',
            'data-url'  =>  $url,
            'data-id'   =>  $model['id'],
            'onkeyup'   =>  "value=value.replace(/[^\d]/g,'')",
            'style'     =>  'padding-left:0;height:25px;width:40px;text-align: center;'
        ], $this->classOptions);

        return Html::textInput($attribute, $model[$attribute], $options);

    }

}