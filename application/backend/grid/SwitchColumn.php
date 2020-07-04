<?php
namespace backend\grid;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
class SwitchColumn extends \yii\grid\ActionColumn {

    /**
     * {@inheritdoc}
     */
    public $header          = '是否启用';
    public $attribute       = 'status';
    public $classOptions    = array();
    public $field           = 'id';
    /**
     * {@inheritdoc}
     */
    //开启或关闭 1开启 0关闭
    protected function renderDataCellContent($model, $key, $index)
    {
        $id     = '';
        if(isset($model[$this->field])){
            $id =  $model[$this->field];
        }
        $url       =  Url::to(['status']);
        $options = array_merge([
            'data-open'     => 1,
            'data-close'    => 0,
            'data-id'       => $id,
            'data-url'      => $url,
            'lay-skin'      => 'switch',
            'lay-filter'    => 'switchShangeStatus',
            'lay-text'      => Yii::t('backend', 'Status Delete').'|'.Yii::t('backend', 'Status Active')
        ], $this->classOptions);
        $attribute =  $this->attribute;
        $checked = $model[$attribute];
        $html = Html::checkbox($attribute,$checked,$options);
        return Html::tag('div',$html,['class'=>'table-switch-status']);

    }




}