<?php

namespace extensions\uploads;
use common\components\Func;
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
class Uploads extends InputWidget
{


    public $clientOptions   = [];
    public $isMove          = false;
    public $msg             = '建议上传100x100像素或等比例尺寸的图片';//'尺寸750x750像素以上，大小2M以下 (可拖拽图片调整显示顺序 )';
    public $amount          = 5;
    public $type            = 'radio';//radio 单项 'checkbox' 多项
    //默认配置
    protected $_options;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        $this->_options = [
            'uploadUrl' => Url::to(['files/browsefile']),
        ];
        $this->clientOptions = ArrayHelper::merge($this->_options, $this->options);

        if($this->hasModel()){
            parent::init();
        }
    }
    public function run() {
        $this->registerClientScript();
        $InputName      = Html::getInputName($this->model, $this->attribute);
        if($this->type == 'checkbox'){
            $InputName .='[]';
        }
        $AttributeValue = Html::getAttributeValue($this->model, $this->attribute);
        $fileItem = "";
        if(!empty($AttributeValue)){

            if($this->type == 'radio') {
            $pic_url = Func::getImageUrl($AttributeValue);
                $fileItem = <<<FILEITEM
                               
                    <div id="{$this->id}" class="img-cover" style="background-image: url('{$pic_url}');"></div>
                    <input type="hidden"  value="{$AttributeValue}" name="{$InputName}">
                   
           
FILEITEM;
            }elseif($this->type=='checkbox'){ //多项
                foreach ($AttributeValue as $k=>$v){
                    $pic_url = Func::getImageUrl($v);
                $fileItem .= <<<FILEITEM
                    <div class="clearfix file-item" draggable="true">                   
                            <div class="img-cover" style="background-image: url('{$pic_url}');"></div>
                            <input type="hidden"  value="{$v}" name="{$InputName}">
                            <i class="layui-icon layui-icon-close file-item-delete"></i>
                    </div>
FILEITEM;
                }
            }
        }else{
            if($this->type == 'radio') {
                $fileItem = <<<FILEITEM
                              
                    <div id="{$this->id}" class="img-cover" ><i class="layui-icon"></i>上传图片</div>
                    <input type="hidden"  value="" name="{$InputName}">
                    
           
FILEITEM;
            }

        }
        $button = '';
    if($this->type == 'checkbox') {
        $button = Html::button('<i class="layui-icon"></i>上传图片', [
            'style'=>'margin-bottom: 5px;',
            'id'=> $this->id,
            'class' => 'layui-btn layui-btn-sm layui-btn-normal ajax-library-popup'
        ]);
        $html = <<<HTML
    {$button}
    <div class="clearfix uploader-list" id="uploader_body_{$this->attribute}">        
         {$fileItem}          
    </div> 
    <div class="help-block uploader-msg">
      <small>{$this->msg}</small>
    </div>
HTML;

    }else{
        $html = <<<HTML
    <div class="clearfix uploader-list" >         
        <div title="点击上传图片" id="uploader_body_{$this->attribute}" class="clearfix file-item ajax-library-popup">  
         {$fileItem}      
         </div>  
    </div> 
    <div class="help-block uploader-msg">
      <small>{$this->msg}</small>
    </div>
HTML;

    }

        if($this->type == 'radio') {
            $layui = <<<LAYUI
                    <script id="uploader_mian_list_{$this->attribute}" type="text/html">
                    {{#  layui.each(d, function(index, item){ }}        
                                  
                            <div class="img-cover" style="background-image: url('{{ item.pic_url }}');"></div>
                            <input type="hidden" value="{{ item.id }}" name="{$InputName}">
                                
                                 
                    {{#  }); }}
                    </script>
LAYUI;
        }else{
            $layui = <<<LAYUI
                    <script id="uploader_mian_list_{$this->attribute}" type="text/html">
                    {{#  layui.each(d, function(index, item){ }}        
                        <div draggable="true" class="clearfix file-item">                
                                <div class="img-cover" style="background-image: url('{{ item.pic_url }}');"></div>
                                <input type="hidden" value="{{ item.id }}" name="{$InputName}">
                                <i class="layui-icon layui-icon-close file-item-delete"></i>
                        </div>            
                    {{#  }); }}
                    </script>
LAYUI;
        }
        return $html.$layui;



    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript() {


    $script = '';

        if($this->type == 'radio' ){ //单项
            $script = <<<EOT
            $(".field-{$this->id}").find('.ajax-library-popup').selectImages({                 
                    url:'{$this->clientOptions['uploadUrl']}',
                    limit:1,
                    done:function (data) {
                        var attr = Array();
                         attr.push(data[0]);
                        layui.use('laytpl', function(){
                        var laytpl = layui.laytpl;
                            var getTpl = uploader_mian_list_{$this->attribute}.innerHTML,view = document.getElementById("uploader_body_{$this->attribute}");
                            laytpl(getTpl).render(attr, function(html){
                              view.innerHTML = html;
                            });
                        });  
                    }
                //});
                 
              }) 
          
EOT;
        }elseif($this->type == 'checkbox'){ //多项
            $script =<<<EOT
                        
            $(".field-{$this->id}").find('.ajax-library-popup').selectImages({   
                    url:'{$this->clientOptions['uploadUrl']}',            
                    limit:{$this->amount},
                    done:function (data) {
                       var attr = Array();
                        //获取当前图片数量
                        var amount =  {$this->amount} - $('#uploader_body_{$this->attribute} .file-item').length;
                        if(amount<=0){
                            layer.msg("最多可以选择{$this->amount}张图片", {
                              icon: 2,
                              time: 3000 
                            });
                            return false;
                        }
                        
                        for(let i=0;i<amount;i++){
                            if(data[i]){
                            attr.push(data[i]);
                            }
                        }  // console.info(attr);              
                        layui.use('laytpl', function(){
                        var laytpl = layui.laytpl; 
                     
                            var getTpl = uploader_mian_list_{$this->attribute}.innerHTML;
                            laytpl(getTpl).render(attr, function(html){
                              $("#uploader_body_{$this->attribute}").append(html);
                            });
                        }); 
                       moveImges();   
                    }
               
                 
              });       
            moveImges();
            $(function(){
                 $('.uploader-list').delegate('.file-item-delete','click' , function(){                
                    $(this).parent().remove();  
                 });
             });
EOT;

            //移动位置
            $script .= <<<EOT
    function swapDom(a, b) { 
        let temp = a.innerHTML;
        a.innerHTML = b.innerHTML;
        b.innerHTML = temp;
    }
    function moveImges(){
        let item    = $('#uploader_body_{$this->attribute} .file-item');        
        let fromDom = null,
            toDom   = null,
            lastDom = null;      
        for(let i=0;i<item.length;i++){   //console.log(i);     
            item[i].ondrag = function(){               
            }
            item[i].ondragstart = function(event){ 
              lastDom = fromDom = this;
            }
            item[i].ondragover = function(event){
                event.preventDefault();
                event.dataTransfer.effectAllowed = "move";
            }
            item[i].ondrop = function(event){                
                fromDom = null;
                toDom = null;
            }
            item[i].ondragend = function(event){                
                toDom = null;
            }
            item[i].ondragenter = function(event){                
                 toDom = this;
                if(fromDom == lastDom){
                    //第一次调换
                    swapDom(lastDom, toDom);
                    lastDom = toDom;
                }else{
                    //第N+1次调换，要先把上一个div的东西还原回去，再跟第三个div互换
                    //这个防止enter多次触发
                    if(lastDom == toDom){return;}
                    swapDom(fromDom,lastDom);
                    swapDom(fromDom,toDom);
                    lastDom = toDom;
                }
            }
        }
        
    } 
EOT;
        }



        $this->view->registerJs($script, View::POS_END);

 //加载Css
$css = <<<CSS
.form-file-upload .layui-btn-normal{background-color: #3bb4f2;border-color:#3bb4f2;}
.uploader-list {user-select: none; padding-bottom: 0;}
.uploader-list .file-item{float: left;position: relative;margin: 3px 13px 0 0;padding: 4px;border: 1px dashed #ddd; background:#fff;}
.uploader-list .file-item .img-cover{cursor: pointer;height: 100px;width: 100px;background: no-repeat center center / 100%;line-height: 100px;text-align: center;color: #999;font-size: 12px;}
.uploader-list .file-item .file-item-delete {position: absolute;top: -10px;right: -10px;cursor: pointer;height: 20px;width: 20px;line-height: 20px;background: rgba(153, 153, 153, 0.7);border-radius: 50%;text-align: center;color: #fff !important;display: none;}
.uploader-list .file-item:hover .file-item-delete{display:inline-block;}
.uploader-list .file-item .file-item-delete:hover {background: #000; }
.uploader-msg small{color:#838fa1;}
CSS;

if($this->type == 'checkbox'){
    $css .= <<<CSS
    #uploader_body_{$this->attribute} .file-item .img-cover{cursor: move !important;}
CSS;


}

 $this->view->registerCss($css);

    }
}