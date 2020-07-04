<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use assets\backendAsset as AppAsset;
AppAsset::register($this);
$assets_url=$this->assetBundles[assets\backendAsset::className()]->baseUrl;

$selectFile = Yii::$app->request->get('selectFile','');
$this->registerJs("
    var selectType = 'checkbox';//'radio';//选择一个

    var param     = $(\"meta[name=csrf-param]\").attr(\"content\");
    var token     = $(\"meta[name=csrf-token]\").attr(\"content\");
    var group_id  = ".$group_id.";
    
    
    
    
    $('.group-add').click(function(){
        var _this,name,id;
        _this = $(this);
        parent.layer.prompt({title: '请输入新分组名称', formType: 3}, function(value, index){            
            parent.layer.close(index);            
            var Url = $('.file-group').attr('data-create-url');
            var data = {                
                'UploadGroup[name]':value,
                'UploadGroup[type]':'image',
                'UploadGroup[sort]':50,
            };
            data[param] = token;
             
            ajax('post',Url,data,function (ret) {
                if(ret.status == true){
                    //console.log()
                    var html = '<li class=\"group-item\" data-group-id=\"'+ret.id+'\" data-group-name=\"'+value+'\">';
                        html += '<a class=\"group-edit\" href=\"javascript:void(0);\" title=\"编辑分组\"><i class=\"layui-icon layui-icon-edit\"></i></a>';
                        html += '<a class=\"group-name text-truncate\" href=\"javascript:void(0);\">'+value+'</a>';
                        html += '<a class=\"group-delete\" href=\"javascript:void(0);\" title=\"删除分组\"><i class=\"layui-icon layui-icon-close-fill\"></i></a></li>';
                    $('.file-group .nav-new').append(html);  
                    layer.msg('新增分组成功', {
                      icon: 1,
                      time: 1000 
                    });    
                }else{
                    layer.msg('新增分组失败', {
                      icon: 2,
                      time: 1000 
                    });
                }            
            });
          
        });
    });
    /**
    更新分组
    **/
    $('.file-group').delegate('.group-edit','click' , function(){
        var _this,name,id;
        _this = $(this);
         name = _this.parent().attr('data-group-name');
         id   = _this.parent().attr('data-group-id');
        parent.layer.prompt({title: '修改分组名称',value:name, formType: 3}, function(value, index){
            parent.layer.close(index);
            var Url = $('.file-group').attr('data-update-url')+'?id='+id;
            var data = {                
                'UploadGroup[name]':value,
                'UploadGroup[id]':id,
            };
            data[param] = token;
           
            ajax('post',Url,data,function (ret) {                  
                if(ret.status == true){
                    _this.parent().attr('data-group-name',value);
                    _this.parent().find('.group-name').html(value);
                    layer.msg('修改成功', {
                      icon: 1,
                      time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }); 
                }else{
                    layer.msg('修改失败', {
                      icon: 2,
                      time: 1000 
                    });
                } 
            });
        });
        
    });
    /**
    删除
    **/
    $('.file-group').delegate('.group-delete','click' , function(){
        var _this,id;
            _this = $(this).parent();         
            id    = _this.attr('data-group-id');
           
            var Url = $('.file-group').attr('data-delete-url')+'?id='+id;
            var data = {               
               
            };
            data[param] = token;
            ajax('post',Url,data,function (ret) {                  
                if(ret.status == true){
                    _this.remove();                     
                }else{
                    layer.msg('修改失败', {
                      icon: 2,
                      time: 1000 
                    });
                } 
            });   
    });
    //选择分组
    $('.file-group').delegate('.group-name','click' , function(){ 
        var _this;
         _this = $(this).parent();  
         _this.addClass('active');
         _this.siblings().removeClass('active');
         group_id = _this.attr('data-group-id');
         var Url = $('.file-group').attr('data-index-url')+'?group_id='+group_id;
         location.href = Url;
    });
    
//上传图片
layui.use('upload', function(){
  var $ = layui.jquery,upload = layui.upload;
  var Url           = $('#uploadfile').attr('data-url');
  var loadMsgIndex  = 0;
    
 //多图片上传
  upload.render({
    elem: '#uploadfile'
    ,url: Url
    ,multiple: true

    ,data:{group_id:group_id}
    ,before: function(obj){
        loadMsgIndex = parent.layer.msg('图片正在上传中...', {
        icon: 16
        ,time: 0
        ,shade : [0.5 , '#000' , true]
      });
    }
    ,error: function (index) { 
       //console.info(index);    
       parent.layer.close(loadMsgIndex);         
       
    }
    ,allDone: function(obj){ //当文件全部被提交后，才触发
       parent.layer.close(loadMsgIndex);
    }
    ,done: function(res){ //console.info(res);        
        if(res.status == true){
            $('.box-body .file-list-item').find('.empty').remove();
            var Html;        
            Html =  '<li title=\"'+res.name+'\" data-file-id=\"'+res.id+'\" data-file-path=\"'+res.pic_url+'\">';    
            Html +=  '<a class=\"file-edit\" href=\"javascript:void(0);\" title=\"编辑分组\"><i class=\"fa fa-edit\"></i></a>';  
            Html +=  '<div class=\"img-cover\" style=\"background-image: url('+res.pic_url+')\"></div>';       
            Html +=  '<p class=\"file-name\">'+res.name+'</p>';        
            Html +=  '<div class=\"select-mask\"><img src=\"{$assets_url}/images/chose.png\"></div>';            
            Html +=  '</li>';
            $('.box-body .file-list-item').prepend(Html);        
            //popup.alert('上传成功');
     
          }else{                
                parent.layer.msg(res.message,{ icon: 2, time: 3000 });
                return false;
          }
        }
  });
});


//选择文件   
    function fileSelect(){
          var li = $('.box-body .file-list-item li.active');
          var ids = '';
          li.each(function(){
             if(ids != ''){
              ids += ','; 
             }
              ids += $(this).attr('data-file-id');
          });
          return ids;
    }
//选择删除
    $('.delete .layui-btn').click(function(){
        var _this   = $(this);
        var ids     = fileSelect();
        if(ids == ''){
            layer.msg('请选择要操作的数据',{
                  icon: 7,
                  time: 1500 
            });
            return false;
        }
        var Url  = $('.box-body').attr('data-delete-url');
        var data = {               
               id:ids
            };
        data[param] = token;           
            ajax('post',Url,data,function (ret) {                  
                if(ret.status == true){
                    $('.box-body .file-list-item li.active').remove();
                    layer.msg('删除成功', {
                      icon: 1,
                      time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }); 
                }else{
                    layer.msg('删除失败', {
                      icon: 2,
                      time: 1000 
                    });
                } 
            });
        
        
    });
    //显示移动至 
    $('.group-select .layui-btn').click(function(){
        var _this = $(this).parent();
        _this.toggleClass('active');
        if(_this.hasClass('active')){
            var item    = $('.file-group .nav-new li.group-item');
            var html    = '<li class=\"dropdown-header\">请选择分组</li>';
            item.each(function(){               
                html += '<li ><a class=\"move-file-group\" data-group-id=\"'+$(this).attr('data-group-id')+'\" href=\"javascript:void(0);\">'+$(this).attr('data-group-name')+'</a></li>';   
            });
            $('.group-select .group-list').html(html);
        }
    });    
    //移动至    
    $('.group-select').delegate('.move-file-group','click' , function(){
        var groupId = $(this).attr('data-group-id');
        var ids     = fileSelect();
        $('.group-select').removeClass('active');
        if(ids == ''){
            layer.msg('请选择要操作的数据',{
                      icon: 7,
                      time: 1500 
            });
            return false;
        }
        var Url  = $('.box-body').attr('data-move-url');
        var data = {               
               id:ids,
               group_id:groupId               
            };
        data[param] = token;           
            ajax('post',Url,data,function (ret) {                  
                if(ret.status == true){   
                    if(group_id>=0){   
                    $('.box-body .file-list-item li.active').remove();  
                    }            
                    layer.msg('操作成功', {
                      icon: 1,
                      time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }); 
                }else{
                    layer.msg('操作失败', {
                      icon: 2,
                      time: 1000 
                    });
                } 
            });
        
    }) 
    //图片编辑
$('.box-body').delegate('.file-edit,.file-name','click' , function(){ 
    var _this,id,name;
        _this = $(this).parent();         
        id    = _this.attr('data-file-id');
        name  = _this.attr('title');
        
        parent.layer.prompt({title: '修改图片名称',value:name, formType: 3}, function(value, index){
            parent.layer.close(index);
            var Url = $('.box-body').attr('data-update-url')+'?id='+id;
            var data = {                
                'UploadFile[name]':value,
                'UploadFile[id]':id,
            };
            data[param] = token;
           
            ajax('post',Url,data,function (ret) {                  
                if(ret.status == true){
                    _this.attr('title',value)
                    _this.find('.file-name').html(value);
                    layer.msg('修改成功', {
                      icon: 1,
                      time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }); 
                }else{
                    layer.msg('修改失败', {
                      icon: 2,
                      time: 1000 
                    });
                } 
            });
        });
        
});

//选择图片
    $('.box-body').delegate('.file-list-item li .img-cover,.file-list-item li .select-mask','click' , function(){
        var _this = $(this).parent();
        _this.toggleClass('active');
        if(selectType == 'radio'){            
            _this.siblings().removeClass('active');
        } 
    });
");
$this->registerJs("
var index = parent.layer.getFrameIndex(window.name);
function librarySelect(options,_this){ 
    
    var li   = $('.box-body .file-list-item li.active');
    var attr = new Array();
    var i    = 0;
      li.each(function(){        
         attr.push({
         id:$(this).attr('data-file-id'),
         name:$(this).attr('title'),
         pic_url:$(this).attr('data-file-path')
         });
         i++; 
         if(i==options.limit){
            return false; 
         }       
      }); 
      //console.log(attr);
    if(attr.length==0){
        layer.msg('请选择图片', {
          icon: 2,
          time: 1000 
        });
        return false;
    }  
    
    if(options.done !== undefined){  
        options.done(attr,_this);
    }else{
         var iframe = window.parent.$('.layui-layer-iframe.win-popup')[0];
        if(iframe === undefined){ 
            var parentWindow = window.parent;
            eval(\"parentWindow.selectFile\"+options+\"(attr)\")
        }else{
            var frameId      = iframe.getElementsByTagName(\"iframe\")[0].id;
            var parentWindow = parent.document.getElementById(frameId).contentWindow;          
            eval(\"parentWindow.selectFile\"+options+\"(attr)\");       
        }
    }
    parent.layer.close(index);  
    return false;
     //console.log(frameId) 
    
}
",\yii\web\View::POS_END);
$css = <<<CSS
    html,body{margin: 0;padding: 0;height:100%;background:#fff;}
    .file-group {float: left;width: 170px;padding-top: 15px;}
    .file-group .nav-new {overflow-y: auto;max-height: 450px;}
    .file-group .nav-new li {position: relative;margin: 3px 0;padding: 8px 30px;}
    .file-group .nav-new li:hover, .file-group .nav-new li.active {background:rgba(48, 145, 242, 0.1);border-radius: 6px;}
    .file-group .nav-new li .text-truncate {word-wrap: normal;text-overflow: ellipsis;white-space: nowrap;overflow: hidden; }
    .file-group .nav-new li a.group-name {color:#595961;  }
    .file-group .nav-new li.active a.group-name{color:#0e90d2;}
    .file-group .nav-new li a.group-edit {display: none;position: absolute;left: 6px;}
    .file-group .nav-new li a.group-delete {display: none;position: absolute; right: 6px; }
    .file-group .nav-new li:hover a.group-edit,.file-group .nav-new li:hover a.group-delete{display: inline-block;}
    .file-group .nav-new li:hover a{color:#0e90d2;}
    .file-group a.group-add {display: block;margin-top: 18px;padding: 0 30px; color: #0e90d2 }
    .file-list {float: left;}
    .file-list .box-header {margin-bottom: 5px;height: 30px;padding: 0 25px 0 10px;}
    .box-body{width: 675px;}
    .box-body ul.file-list-item {overflow-y: auto; height: 420px;}
    .box-body ul.file-list-item li {position: relative; cursor: pointer; border-radius: 6px;padding: 10px; border: 1px solid rgba(0, 0, 0, 0.05);float: left;margin: 10px;   -webkit-transition: All 0.2s ease-in-out;-moz-transition: All 0.2s ease-in-out;-o-transition: All 0.2s ease-in-out; transition: All 0.2s ease-in-out; }
    .box-body ul.file-list-item li:hover { border: 1px solid #16bce2; }
    .box-body ul.file-list-item li .img-cover {width: 120px;height: 120px;background: no-repeat center center / 100%; }
    .box-body ul.file-list-item li p.file-name {margin: 5px 0 0 0;width: 120px; word-wrap: normal;text-overflow: ellipsis; white-space: nowrap;overflow: hidden;}
    .box-body ul.file-list-item li.active .select-mask {display: block; }
    .box-body ul.file-list-item li .select-mask { display: none; position: absolute; top: 0; bottom: 0;left: 0; right: 0;background: rgba(0, 0, 0, 0.5);text-align: center;border-radius: 6px; }
    .box-body ul.file-list-item li .select-mask img {position: absolute;top: 50px;left: 45px; }
    /**文件编辑**/
    .box-body ul.file-list-item li .file-edit{position: absolute;z-index: 1;right: 3px;top: 3px;display: none;}
    .box-body ul.file-list-item li.active .file-edit{color: #fff;}
    .box-body ul.file-list-item li:hover .file-edit{display: block;}
    .box-header .uploadfile{height: 32px;line-height: 32px;border-color: #2589ff;color: #2589ff;}
    .box-header .uploadfile:hover{background: #1E9FFF;border-color: #1E9FFF;color: #fff;}
    .box-header .group-select{}
    .box-header .group-select .layui-btn{height: 28px;line-height: 28px;padding: 0 2px 0 8px;border-color:#3bb4f2;background: #3bb4f2;color:#fff;}
    .box-header .delete{margin-left: 10px;}
    .box-header .delete .layui-btn{border-color:#e7505a;color:#e7505a; height: 28px;line-height: 28px;padding: 0 8px;}
    .box-header .delete .layui-btn:hover{background: #e7505a;border-color: #e7505a;color: #fff;}
    .box-header .dropdown{position: relative;display: inline-block;}
    .box-header .dropdown .dropdown-content {position: absolute; top: 100%;left: 0;z-index: 1020;display: none;float: left;min-width: 160px;padding: 10px 0;margin: 9px 0 0;text-align: left;line-height: 1.6;background-color:#fff;border: 1px solid #ddd; border-radius: 0;-webkit-background-clip: padding-box; background-clip: padding-box;-webkit-animation-duration: .15s;animation-duration: .15s;}
    .box-header .dropdown.active > .dropdown-content { display: block;}
    .box-header .dropdown .dropdown-content:before,.box-header .dropdown .dropdown-content:after {position:absolute;display:block;content:"";width:0;height:0;border:8px dashed transparent;z-index:1}}
    .box-header .dropdown .dropdown-content:after, .box-header .dropdown .dropdown-content:before { border-bottom-style:solid;border-width:0 8px 8px }
    .box-header .dropdown .dropdown-content:before {border-bottom-color:#ddd;bottom:0 }
    .box-header .dropdown .dropdown-content:after{border-bottom-color:#fff;bottom:-1px}
    .box-header .dropdown .dropdown-content:after, .box-header .dropdown .dropdown-content:before { left: 10px; top: -16px; pointer-events: none; }
    .box-header .dropdown .dropdown-content:after{top:-14px}
    .box-header .dropdown .dropdown-content:first-child {margin-top: 0;}
    .box-header .dropdown .dropdown-header {display: block;padding: 6px 20px;font-size: 13px;color:#999;}
    .box-header .dropdown ul.dropdown-content > li > a {display:block;padding:6px 20px;clear:both;font-weight:400;color:#333;white-space:nowrap}
    .box-header .dropdown ul.dropdown-content>li>a:focus,.box-header .dropdown ul.dropdown-content>li>a:hover{text-decoration:none;color:#262626;background-color:#f5f5f5}
    .box-header .dropdown ul.dropdown-content>.active>a,.box-header .dropdown ul.dropdown-content>.active>a:focus,.box-header .dropdown ul.dropdown-content>.active>a:hover{color:#333;text-decoration:none;outline:0;background-color:#f5f5f5}
    .pagination{border-width:0;}
.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus{
    background-color:#3bb4f2;border-color:#3bb4f2;
}
.pagination > li > a:hover{color: #3bb4f2}
CSS;

$this->registerCss($css);
$model      = $dataProvider->getModels();
?>
<div class="file-group" data-index-url="<?=Url::to(['browsefile'])?>" data-create-url="<?=Url::to(['files-group/create'])?>" data-update-url="<?=Url::to(['files-group/update'])?>" data-delete-url="<?=Url::to(['files-group/delete'])?>" >
    <ul class="nav-new">
        <li class="<?=$group_id==-1?'active':'';?>" data-group-id="-1">
            <a class="group-name text-truncate" href="javascript:void(0);" title="全部">全部</a>
        </li>
        <li class="<?=$group_id==0?'active':'';?>" data-group-id="0">
            <a class="group-name text-truncate" href="javascript:void(0);" title="未分组">未分组</a>
        </li>
        <?php
        foreach ($filesGroup as $groupKey=> $groupName):
        ?>
        <li class="group-item <?=$group_id==$groupKey?'active':'';?>" data-group-id="<?=$groupKey?>" data-group-name="<?=$groupName?>">
            <a class="group-edit" href="javascript:void(0);" title="编辑分组">
                <i class="layui-icon layui-icon-edit"></i>
            </a>
            <a class="group-name text-truncate" href="javascript:void(0);">
                <?=$groupName?>
            </a>
            <a class="group-delete" href="javascript:void(0);" title="删除分组">
                <i class="layui-icon layui-icon-close-fill"></i>
            </a>
        </li>
        <?php
        endforeach;
        ?>
    </ul>
    <a class="group-add" href="javascript:void(0);">新增分组</a>
</div>
<div class="file-list">
    <div class="box-header">
            <div class="pull-left ">
                <div class="pull-left group-select dropdown">
                    <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">
                        移动至<i class="layui-icon layui-icon-triangle-d"></i>
                    </button>
                    <ul class="group-list dropdown-content">
                        <li class="dropdown-header">请选择分组</li>
                    </ul>

                </div>
                <div class="pull-left delete">
                    <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">
                        <i class="layui-icon"></i>删除
                    </button>
                </div>
            </div>
            <div class="pull-right">
                <button data-url="<?=Url::to(['Uploads', 'action' => 'uploadJson']);?>" id="uploadfile" type="button" class="layui-btn layui-btn-primary layui-btn-sm uploadfile">
                    <i class="layui-icon"></i>上传图片
                </button>
            </div>
    </div>
    <!--内容-->
    <div id="file-list-body" class="box-body" data-move-url="<?=Url::to(['move'])?>" data-delete-url="<?=Url::to(['del'])?>" data-update-url="<?=Url::to(['update']);?>">
        <?php Pjax::begin(); ?>
        <ul class="file-list-item" >

            <?php
            if(!empty($model)){
                foreach ($model as $val):
                ?>
                <li title="<?=$val->name?>" data-file-id="<?=$val->id?>" data-file-path="<?=$val->save_url?>">
                    <a class="file-edit" href="javascript:void(0);" title="编辑分组">
                        <i class="fa fa-edit"></i>
                    </a>
                    <div class="img-cover" style="background-image: url('<?=$val->save_url?>')">
                    </div>
                    <p class="file-name"><?=$val->name?></p>
                    <div class="select-mask">
                        <img src="<?=$assets_url?>/images/chose.png">
                    </div>
                </li>
                <?php
                endforeach;
            }else{
                ?>
                 <div style="padding-top:80px;text-align:center;" class="empty">
                     <i  style="font-size: 150px;color: #eee" class="layui-icon layui-icon-face-surprised"></i>
                     <div style="line-height:30px;color: #999;" class="">没有找到数据</div>
                 </div>
                <?php
            }
            ?>

        </ul>
        <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>'{pager}',
            'pager'=>[
                //'options'=>['class'=>'layui-box layui-laypage layui-laypage-default'],
                'firstPageLabel'=>"第一页",
                'prevPageLabel'=>'上一页',//'Prev',
                'nextPageLabel'=>'下一页',//'Next',
                'lastPageLabel'=>'最后一页',
            ],
        ])?>
        <?php


        Pjax::end(); ?>
    </div>
    <!--End 内容-->
</div>
