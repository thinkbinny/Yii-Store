<?php

namespace backend\components\relation;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\base\InvalidParamException;
/**
 *  上传图片插件
 *
 * @author ThinkBinny <274397981@QQ.com>
 */
class Region extends InputWidget
{




    public $url;
    //默认配置
    public $table;

    /**
     * @var string 此属性不用处理
     */
    public $attribute;
    /**
     * @var array 省份配置
     */
    public $province = [];

    /**
     * @var array 城市配置
     */
    public $city = [];

    /**
     * @var array 县/区配置
     */
    public $district = [];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        /*$this->_options = [
            'url' => Url::to(['public/map']),
            'style'=>'width:300px;display:inline-block',
            'class'=>'layui-input',
        ];
        $this->clientOptions = ArrayHelper::merge($this->_options, $this->options);

        if($this->hasModel()){
            parent::init();
        }*/
        if (!$this->model) {
            throw new InvalidParamException('model不能为null!');
        }

        if (empty($this->province) || empty($this->city)) {
            throw new InvalidParamException('province和city不能为空！');
        }

        if(!isset($this->province['items'])){ //添加默认省份
            $this->province['items'] = $this->table::getRegion();
        }
        if(!isset($this->city['items'])){ //添加默认省份
            $province = Html::getAttributeValue($this->model, $this->province['attribute']);
            $this->city['items'] = $this->table::getRegion($province);
        }
        if(!empty($this->district)){
            if(!isset($this->district['items'])){ //添加默认省份
                $city = Html::getAttributeValue($this->model, $this->city['attribute']);
                $this->district['items'] = $this->table::getRegion($city);
            }
        }

        if (empty($this->city['options']['prompt'])) {
            $this->city['options']['prompt'] = '选择城市';
        }




        if (!empty($this->district)) {
            if (empty($this->district['options']['prompt'])) {
                $this->district['options']['prompt'] = '选择县/区';
            }

            $this->city['options'] = ArrayHelper::merge($this->city['options'], [
                'lay-filter' => $this->city['attribute']
            ]);
        }

        $this->province['options'] = ArrayHelper::merge($this->province['options'], [
            'lay-filter' => $this->province['attribute']
        ]);


    }
    public function run() {
        $this->registerClientScript();
       /* $InputName      = Html::getInputName($this->model, $this->attribute);
        $AttributeValue = Html::getAttributeValue($this->model, $this->attribute);

        */


        $output[] = Html::activeDropDownList($this->model, $this->province['attribute'], $this->province['items'],
            $this->province['options']);
        $output[] = Html::activeDropDownList($this->model, $this->city['attribute'], $this->city['items'],
            $this->city['options']);
        if (!empty($this->district)) {
            $output[] = Html::activeDropDownList($this->model, $this->district['attribute'], $this->district['items'],
                $this->district['options']);
        }

        return @implode("\n", $output);


    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript() {
        $joinChar = strripos($this->url, '?') ? '&' : '?';
        $url = $this->url . $joinChar;

        $districtId      = Html::getInputId($this->model, $this->district['attribute']);
        $districtDefault = Html::renderSelectOptions('district', ['' => $this->district['options']['prompt']]);

        if (!empty($this->district)) {
            $city = <<<JS
            form.on('select({$this->city['attribute']})', function(data){   
                var val=data.value;
                if(val != ''){
                        $.get('{$url}parent_id='+val, function(data) {
                            $('#{$districtId}').html('{$districtDefault}'+data);
                            form.render('select');
                        })
                  }else{
                        $('#{$districtId}').html('{$districtDefault}');
                        form.render('select');
                }
                
               
            });
JS;
        }

        $cityId = Html::getInputId($this->model, $this->city['attribute']);
        $cityDefault = Html::renderSelectOptions('city', ['' => $this->city['options']['prompt']]);

        $script = <<<JS
        layui.use(['form'], function() {
            form    =   layui.form;
            form.on('select({$this->province['attribute']})', function(data){  
                 var val    =   data.value;
                if(val != ''){
                    $.get('{$url}parent_id='+val, function(data) {
                        $('#{$cityId}').html('{$cityDefault}'+data);
                        form.render('select');
                    })
                }else{
                    $('#{$cityId}').html('{$cityDefault}');
                    form.render('select');
                }
                $('#{$districtId}').html('{$districtDefault}');                
                form.render('select');
               
            });
            {$city}
            
            
        });

JS;


        $css = <<<CSS
        .field-{$this->id} select{display:inline-block ;width: 0;border: none;}
        .field-{$this->id} .layui-form-select {display: inline-block}
CSS;

        $this->view->registerCss($css);

        $this->view->registerJs($script, View::POS_END);
    }

}