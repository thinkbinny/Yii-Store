<?php
namespace extensions\umeditor;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

use extensions\umeditor\assets\UmeditorAsset;


class Umeditor extends InputWidget
{
    /**
     * 编辑器传参配置(配置查看百度编辑器（ueditor）官方文档)
     */
    public $options = [];
    
    /**
     * 编辑器默认基础配置
     */
    public $_init;
    
    public function init()
    {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        
        $this->_init = [
            //'serverUrl' => Url::to(['ueditor']),
            'lang' => (strtolower(\Yii::$app->language) == 'en-us') ? 'en' : 'zh-cn',
            'initialFrameWidth' => '100%',
            'initialFrameHeight' => '600',


        ];
        $this->options = ArrayHelper::merge($this->_init, $this->options);
        //parent::init();
    }
    
    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            return Html::activeTextarea($this->model, $this->attribute, ['id' => $this->id]);
        } else {
            return Html::textarea($this->id, $this->value, ['id' => $this->id]);
        }
    }
    
    /**
     * 注册Js
     */
    protected function registerClientScript()
    {
        $uploadUrl  = Url::to(['files/browsefile']);
        $library    = <<<EOT
              
             var Url      = "{$uploadUrl}";
             parent.layer.open({
                title:'图片库',
                skin: 'layui-layer-library',
                id: 'layui-layer-library',
                shade: 0.8,
                type: 2,
                shadeClose:false,
                btn: ['确定', '关闭'],
                btn2: function(index){
                    parent.layer.closeAll(index);
                },
                btnAlign: 'r',
                scrollbar:false,
                offset: 'auto',
                area: ['900px', '600px'],
                content: [Url , 'no'] ,
                success: function(layero, index){
                    
                },
                yes: function(index, layero){                    
                    var frameId= layero[0].getElementsByTagName("iframe")[0].id;
                    parent.document.getElementById(frameId).contentWindow.librarySelect("{$this->attribute}");                    
                    return false;
                }
             });
             return false;
EOT;

        UmeditorAsset::register($this->view);
        $options = Json::encode($this->options);
        $script = "var um = UM.getEditor('" . $this->id . "', " . $options . ");";
        $script .= <<<JS
           UM.registerUI('image',function(name){
            var me = this;
                
                var btn = $.eduibutton({
                    icon : name,
                    click : function(){ 
                        {$library}
                    },                  
                    title: this.getLang('labelMap')[name] || ''
                });
            
                return btn;
            });
        //返回数据
            function selectFile{$this->attribute}(data){
                
                for (var i = 0; i < data.length; i++) {  
                    console.log(data[i]);  
                    um.execCommand('insertHtml', '<img alt="' + data[i].name + '" src="' + data[i].pic_url + '">');  
                }          
               // um.execCommand('insertHtml', '<img src="" alt="">');            
           
            } 
JS;
        $this->view->registerCss("
            .layui-form-item .edui-editor-body img{max-width: 100%;}
        ");
        $this->view->registerJs($script, View::POS_END);

    }
}
