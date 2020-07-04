<?php
namespace backend\components\select;
use common\components\Func;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;
use yii\helpers\ArrayHelper;
use yii\web\View;
use backend\components\select\assets\SelectAsset;
/**
 *  选择插件插件
 *
 * @author ThinkBinny <274397981@QQ.com>
 */
class Select extends InputWidget
{
    public $readonly        = false;
    public $clientOptions   = [];
    public $valueText       = '';
    public $type            = 'image';//radio 单项 'checkbox' 多项
    //默认配置
    protected $_options;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        $this->_options = [
            'url'   => Url::to(['user/select']),
            'title' => '选择用户',
            'width' => '300px;',
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
        $html = '';

        if($this->type == 'text'){

            $html  = <<<HTML
                <div class="layui-unselect layui-form-select ajax-select-popup" id="selete_body_{$this->attribute}" style="width:{$this->clientOptions['width']} ">
                    <div class="layui-select-title">
                        <input id="{$this->id}" type="hidden" value="{$AttributeValue}" name="{$InputName}"> 
                        <input type="text" placeholder="{$this->clientOptions['title']}" value="{$this->valueText}" readonly="" class="layui-input layui-unselect">
                        <i class="layui-edge"></i>
                    </div>               
                </div>
HTML;

            $html .= <<<HTML
       

        <script id="select_mian_list_{$this->attribute}" type="text/html">
        {{#  layui.each(d, function(index, item){ }} 
            
            <div class="layui-select-title">
                <input id="{$this->id}" type="hidden" value="{{ item.id }}" name="{$InputName}"> 
                <input type="text" placeholder="{$this->clientOptions['title']}" value="{{item.name}}" readonly="" class="layui-input layui-unselect">
                <i class="layui-edge"></i>
            </div>               
             
        {{#  }); }}
        </script>
HTML;
            return $html;
        }





        if(!empty($AttributeValue)){
            $user = Func::getMemberInfo($AttributeValue);
            if($this->readonly == true){
            $html = <<<HTML
                <div class="selete-data-info" id="selete_body_{$this->attribute}">
                    <div  class="ajax-select-win-popup clearfix">
                            <a title="{$user['nickname']} (ID:{$AttributeValue})" style="background-image: url('{$user['headimgurl']}');" href="javascript:void(0);" class="img-cover">&nbsp;</a>
                            <input id="{$this->id}" type="hidden" value="{$AttributeValue}" name="{$InputName}">                    
                    </div>
                    <div class="help-block">
                     <small>不可更改</small>
                    </div>
                </div> 
HTML;
            }else{
                $html = <<<HTML
                <div class="selete-data-info" id="selete_body_{$this->attribute}">
                    <div  class="ajax-select-win-popup ajax-select-popup clearfix">
                            <a title="{$user['nickname']} (ID:{$AttributeValue})" style="background-image: url('{$user['headimgurl']}');" href="javascript:void(0);" class="img-cover">&nbsp;</a>
                            <input id="{$this->id}" type="hidden" value="{$AttributeValue}" name="{$InputName}">                    
                    </div>
                    <div class="help-block">
                     
                    </div>
                </div> 
HTML;
            }
        }
        $update_html = '';
        if($this->readonly==true){
            $update_html = '<small>选择后不可更改</small>';
        }

        if(empty($html) ){
            $html = <<<HTML
                <div class="selete-data-info" id="selete_body_{$this->attribute}">
                    <div class="ajax-select-win-popup ajax-select-popup clearfix">
                            <a href="javascript:void(0);" class="img-cover"><i class="layui-icon layui-icon-username"></i>请选择</a>
                            <input id="{$this->id}" type="hidden" value="{$AttributeValue}" name="{$InputName}">                    
                    </div>
                    <div class="help-block">
                      {$update_html}
                    </div>
                </div>
HTML;

        }

       $html .= <<<HTML
       

        <script id="select_mian_list_{$this->attribute}" type="text/html">
        {{#  layui.each(d, function(index, item){ }} 
            <div class="ajax-select-win-popup ajax-select-popup clearfix">
                <a title="{{item.name}} (ID:{{item.id}})" href="javascript:void(0);" class="img-cover" style="background-image: url('{{ item.pic_url }}');">&nbsp;</a>
                <input id="{$this->id}" type="hidden" value="{{ item.id }}" name="{$InputName}">                    
            </div>
            <div class="help-block">
              {$update_html}
            </div>       
        {{#  }); }}
        </script>
HTML;

        return $html;
    }
    /**
     * 注册Js
     */
    protected function registerClientScript()
    {

        SelectAsset::register($this->view);

        $script = <<<EOT
        $(".field-{$this->id}").delegate('.ajax-select-popup','click' , function(){ 
             var Url      = "{$this->clientOptions['url']}";
             parent.layer.open({
                title:"{$this->clientOptions['title']}",
                skin: 'layui-layer-library',
                id: 'layui-select-win-popup',
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
                    parent.document.getElementById(frameId).contentWindow.submitSelect("{$this->attribute}");                    
                    return false;
                }
             });
             return false;
          }); 
          
         
         function selectFile{$this->attribute}(data){
               // console.log(data)
                var attr = Array();            
                attr.push(data[0]);            
                //console.log(attr);
                layui.use('laytpl', function(){
                var laytpl = layui.laytpl;          
                    var getTpl = select_mian_list_{$this->attribute}.innerHTML,view = document.getElementById("selete_body_{$this->attribute}");
                    laytpl(getTpl).render(attr, function(html){
                      view.innerHTML = html;
                    });
                }); 
             } 
         
         
           
EOT;

        $this->view->registerJs($script, View::POS_END);
    }
}
?>