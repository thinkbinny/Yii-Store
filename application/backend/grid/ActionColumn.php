<?php
namespace backend\grid;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
class ActionColumn extends \yii\grid\ActionColumn {
    public $template = '{view} {update} {auth} {delete}';
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $buttonOptions = [ ];
                if(isset($this->buttonOptions['view'])){
                    $buttonOptions = array_merge($buttonOptions,$this->buttonOptions['view']);
                }
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-info btn-xs',
                ], $buttonOptions);
                return Html::a('<span class="fa fa-eye"></span> '.Yii::t('yii', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $buttonOptions = [ ];
                if(isset($this->buttonOptions['update'])){
                    $buttonOptions = array_merge($buttonOptions,$this->buttonOptions['update']);
                }
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-xs',
                ], $buttonOptions);
                return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
            };
        }
        if (!isset($this->buttons['auth'])) {
            $this->buttons['auth'] = function ($url, $model, $key) {
                $buttonOptions = [ ];
                if(isset($this->buttonOptions['auth'])){
                    $buttonOptions = array_merge($buttonOptions,$this->buttonOptions['auth']);
                }
                $options = array_merge([
                    'title' => Yii::t('backend', 'Auth'),
                    'aria-label' => Yii::t('backend', 'Auth'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-success btn-xs',
                ], $buttonOptions);
                return Html::a('<span class="fa fa-user"></span> '.Yii::t('backend', 'Auth'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {

                $buttonOptions = [ ];
                if(isset($this->buttonOptions['delete'])){
                    $buttonOptions = array_merge($buttonOptions,$this->buttonOptions['delete']);
                }
                $id     = '';


                if( is_array( $model ) ){
                    if(isset($model['id'])){
                        $id =  $model['id'];
                    }
                }else{
                    if(isset($model->id)){
                        $id =  $model->id;
                    }
                }

                $options = array_merge([
                    'title'         => Yii::t('yii', 'Delete'),
                    'aria-label'    => Yii::t('yii', 'Delete'),
                    'data-confirm'  => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-id'       => $id,
                    'class'         => 'btn btn-danger btn-xs ajax-delete confirm',
                ], $buttonOptions);
                return Html::a('<span class="fa fa-times"></span> '.Yii::t('yii', 'Delete'), $url, $options);
            };
        }

    }

}