<?php

namespace backend\components\map;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;
use yii\helpers\ArrayHelper;
use yii\web\View;
/**
 *  上传图片插件
 *
 * @author ThinkBinny <274397981@QQ.com>
 */
class Map extends InputWidget
{


    public $clientOptions   = [];

    //默认配置
    protected $_options;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        $this->_options = [
            'url' => Url::to(['public/map']),
            'style'=>'width:300px;display:inline-block',
            'class'=>'layui-input',
        ];
        $this->clientOptions = ArrayHelper::merge($this->_options, $this->options);

        if($this->hasModel()){
            parent::init();
        }
    }
    public function run() {
        $this->registerClientScript();
        $InputName      = Html::getInputName($this->model, $this->attribute);

        $AttributeValue = Html::getAttributeValue($this->model, $this->attribute);
        $html = <<<HTML
        <input type="text" id="{$this->id}" class="{$this->clientOptions['class']}" style="{$this->clientOptions['style']}" value="{$AttributeValue}" name="{$InputName}" placeholder="请输入详细地址" >
        <button type="button" class="layui-btn layui-btn-normal">选择</button>    
HTML;

        return $html;



    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript() {
        $script = <<<JS
        $(".field-{$this->id}").delegate('.layui-btn','click' , function(){ 
             var Url      = "{$this->clientOptions['url']}";
             parent.layer.open({
                title:'选择坐标',
                skin: '',
                id: 'layui-layer-map',
                shade: 0.8,
                type: 2,
                shadeClose:true,
                btn: ['确定'],                
                btnAlign: 'r',
                scrollbar:false,
                offset: 'auto',
                area: ['935px', '705px'],
                content: [Url , 'no'] ,
                
                yes: function(index, layero){                  
                   var frameId= layero[0].getElementsByTagName("iframe")[0].id;
                    parent.document.getElementById(frameId).contentWindow.submitSelect();                    
                    return false;
                }
             });
             return false;
          });
        function setCoordinate(coordinate){
            $("#{$this->id}").val(coordinate);
        }
JS;

        $this->view->registerJs($script, View::POS_END);
    }

}