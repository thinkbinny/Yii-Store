/**
 * jquery全局函数封装
 */
(function ($) {
    $.fn.extend({
        selectImages: function (option) {

            $(this).unbind().click(function () {
                var _this = $(this);
                var defaults = {
                    title:'图片库',
                    url:_this.attr('data-url'),
                    limit: 1        // 返回 图片数量
                    , done: null  // 选择完成后的回调函数
                }
                    , options = $.extend({}, defaults, option);


                parent.layer.open({
                    title:options.title,
                    skin: 'layui-layer-library',
                    id: 'layui-layer-library',
                    shade: 0.8,
                    type: 2,
                    anim:3,
                    shadeClose:false,
                    btn: ['确定', '关闭'],
                    btn2: function(index){
                        parent.layer.closeAll(index);
                    },
                    btnAlign: 'r',
                    scrollbar:false,
                    offset: 'auto',
                    area: ['900px', '600px'],
                    content: [options.url , 'no'] ,
                    success: function(layero, index){

                    },
                    yes: function(index, layero){
                        var frameId= layero[0].getElementsByTagName("iframe")[0].id;
                        parent.document.getElementById(frameId).contentWindow.librarySelect(options,_this);
                        return false;
                    }
                });
                return false;
                });

        },
        //打开页面
        selectWindows:function (option) {
            $(this).unbind().click(function () {
                var _this = $(this);
                var url     = _this.attr('data-url');
                if( _this.data('attribute') !== undefined && _this.data('attribute-value') !== undefined){
                    url += "?"+_this.data('attribute')+"="+_this.data('attribute-value');
                }

                var defaults = {
                    url:url,
                    title:'标题',
                    skin:'layui-layer-background-none',//'layui-layer-library',
                    shadeClose:false,
                    scrollbar:'no',
                    anim:3,
                    btn : ['确定', '关闭'],
                    area: ['750px', '450px'],
                    params:null,
                     done: null  // 选择完成后的回调函数
                }, options = $.extend({}, defaults, option);

                parent.layer.open({
                    title:options.title,
                    skin: options.skin,
                    shade: 0.8,
                    type: 2,
                    anim:options.anim,
                    shadeClose:options.shadeClose,
                    btn: options.btn,
                    btn2: function(index){
                        parent.layer.closeAll(index);
                    },
                    btnAlign: 'r',
                    scrollbar:false,
                    offset: 'auto',
                    area: options.area,
                    content: [options.url , options.scrollbar] ,//, 'no' Yes
                    success: function(layero, index){
                        try{
                            var frameId= layero[0].getElementsByTagName("iframe")[0].id;
                            parent.document.getElementById(frameId).contentWindow.mySuccess(options,_this);
                        }
                        catch(err)
                        {
                            //在此处理错误
                        }
                    },
                    yes: function(index, layero){
                        try{
                            var frameId= layero[0].getElementsByTagName("iframe")[0].id;
                            parent.document.getElementById(frameId).contentWindow.mySubmit(option,_this);
                        }
                        catch(err)
                        {
                            parent.layer.close(index);
                            //在此处理错误
                        }
                        return false;
                    }
                });
                return false;


            });
        }
    });

})(jQuery);



function locationUrl(Url) {
    if(undefined === parent.$(".layui-tab-item.layui-show").find("iframe")[0]){
        if(Url==='' || Url === null || Url === undefined || Url === 'undefined') {
            parent.window.location.reload();
        }else{
            parent.window.location.href=Url;
        }
    }else{
        if(Url==='' || Url === null || Url === undefined || Url === 'undefined') {
            parent.$(".layui-tab-item.layui-show").find("iframe")[0].contentWindow.location.reload();
        }else{
            parent.$(".layui-tab-item.layui-show").find("iframe")[0].contentWindow.location.href = Url;
        }

    }
}
var popup = {
    alert:function(message,width){
        parent.layer.msg(message, {icon: 0,scrollbar: false,offset: ['45%','45%'],area: ['auto', '66px;']});
    },
    success:function (message,Url) {
        //success
        parent.layer.alert(message, {icon: 1,scrollbar: false},function (index) {
            locationUrl(Url);
        });

    },
    error:function (message,Url) {
        parent.layer.alert(message, {icon: 2,scrollbar: false},function (index) {
            locationUrl(Url);
        });
    }
};

function ajax(Method,Url,data,callBack){
    $.ajax({
        type:Method,
        url:Url,
        data:data,
        beforeSend: function () {
            parent.layer.load(0,{shade: [0.2, '#000'],scrollbar: false,area: ['60px', '30px']});
        },
        complete: function () {
            parent.layer.closeAll('loading');
        },
        success: callBack,
        error: function (e, jqxhr, settings, exception) {
            parent.layer.msg('服务器异常！', function(){

            });
        }
    });
}

function ajaxSubmit(Method,Url,data){

    ajax(Method,Url,data,function (data) {
        if(data.status === 1 || data.status === true){
            popup.success(data.message,data.url);
        }else{
            if(data.url!=='' && data.url!==null && data.url!==undefined){
                popup.error(data.message,data.url);
            }else{
                popup.alert(data.message);
            }
        }
    });
}

function ajaxSubmitCallBack(Url,Data,callBack) {
    ajax('post',Url,Data,function (ret) {
        if(ret.status === false){
            var errors = ret.message;
            if( typeof(errors) === 'string' ){
                layer.msg(errors, {icon: 5});
                return false;
            }
            for(var key in errors){
                var err = errors[key];
                //console.log(key);
                if($('#'+key).css("display")==='none'){
                    var name = $('#'+key).parent();
                    layer.tips(err[0], name, {
                        tips: [1, '#e74c3c']
                        //tipsMore: true
                    });
                    name.focus();
                }else{
                    layer.tips(err[0], '#'+key, {
                        tips: [1, '#e74c3c']
                        //tipsMore: true
                    });
                    $('#'+key).focus();
                }
                return false;
            }
        }else{ //成功
            callBack(ret);
        }
    });
}

$(function(){
    $(".layui-submit-ajax").submit(function () {
        var layerIndex=0,_this;
        _this = $(this);
        if(window.name !== ''){
            try
            {
                layerIndex = parent.layer.getFrameIndex(window.name);
            }
            catch(err)
            {
            }

        }
        var Url   = _this.attr('action');
        var Data  = _this.serialize();
        ajaxSubmitCallBack(Url,Data,function (ret) {
            if(ret.status === true){
                parent.layer.msg(ret.message, {icon: 1,time:1000}, function(){
                    if(layerIndex !== 0){
                        parent.layer.close(layerIndex);
                    }
                    locationUrl(ret.url);
                });
            }else{
                layer.msg('系统脚本出错', {icon: 5});
                return false;
            }
        });
        return false;
    });

    //POST 提交
    $('.ajax-post').click(function(){
        var param,token,_this;
        _this     = $(this);
        param     = $("meta[name=csrf-param]").attr("content");
        token     = $("meta[name=csrf-token]").attr("content");
        var target,query,form;
        var target_form = _this.attr('target-form');
        var message     = _this.attr('data-alert');
        if(message=== '' || message === null || message === undefined){ message =  '确认要执行该操作吗?';}
        var nead_confirm=false;
        if( (_this.attr('type')==='submit') || (target = _this.attr('data-url'))  || (target = _this.attr('href'))){
            form = $('.'+target_form);query = form.serialize();
            if (_this.attr('hide-data') === true){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)===undefined){
                //链接提交
                query  = {t:Math.random()};
                query[param] = token;
                target = _this.attr('href');
                if ( _this.hasClass('confirm') ) {
                    parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        ajaxSubmit('post',target,query);
                        parent.layer.close(index);
                        return false;
                    });
                    return false;
                }
                return false;
            }else if ( form.get(0).nodeName === 'FORM' ){
                if(_this.attr('data-url') !== undefined){
                    target = _this.attr('data-url');
                }else{
                    target = form.get(0).action;
                }
                query = form.serialize();
                if ( _this.hasClass('confirm') ) {
                    parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        ajaxSubmit('post',target,query);
                        parent.layer.close(index);
                        return false;
                    });
                    return false;
                }
            }else if( form.get(0).nodeName === 'INPUT' || form.get(0).nodeName === 'SELECT' || form.get(0).nodeName === 'TEXTAREA') {
                form.each(function(k,v){
                    if(v.type==='checkbox' && v.checked===true){
                        nead_confirm = true;
                    }
                });
                query = form.serialize();
                if ( nead_confirm && _this.hasClass('confirm') ) {
                    parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        ajaxSubmit('post',target,query);
                        parent.layer.close(index);
                        return false;
                    });
                    return false;
                }
            }else{
                query = form.find('input,select,textarea').serialize();
                if ( _this.hasClass('confirm') ) {
                    parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        ajaxSubmit('post',target,query);
                        parent.layer.close(index);
                        return false;
                    });
                    return false;
                }
            }
            ajaxSubmit('post',target,query);
            return false;
        }
        return false;
    });
    /**
     * GET提交
     */
    $('.ajax-get').click(function(){
        var target,dataUrl,dataHref;
        var _this = $(this);
        dataUrl = _this.attr('data-url');
        dataHref = _this.attr('href');

        if(dataUrl!=='' && dataUrl!==undefined && dataUrl!==null){
            target = dataUrl;
        }else if(dataHref!=='' && dataHref!==undefined && dataHref!==null){
            target = dataHref;
        }else{
            popup.alert('参数出错！');return false;
        }
        var t = Math.random();
        if ( _this.hasClass('confirm') ) {
            var message = _this.attr('data-alert');
            if(message==='' || message===null || message === undefined){ message =  '确认要执行该操作吗?';}
            parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){

                    ajaxSubmit('get',target,{t:t});

                parent.layer.close(index);
                return false;
            });
            return false;
        }

            ajaxSubmit('get',target,{t:t});

        return false;
    });

    //状态提交
    $('.ajax-status-post').click(function () {
        var _this = $(this);
        var text,id,value,param,token,Url,name,icon;
        Url       = _this.attr('data-url');
        param     = $("meta[name=csrf-param]").attr("content");
        token     = $("meta[name=csrf-token]").attr("content");
        text      = _this.attr('data-text');
        icon      = _this.attr('data-icon');
        if(text===undefined){
            text      = _this.html();
            text      = text.replace(/&nbsp;/g,'');
        }
        value     = _this.attr('data-value');
        name      = _this.attr('data-name');
        id        = _this.attr('data-id');
        if(id === undefined){
            var _form = _this.attr('data-form');
            id        = $('.'+_form).serialize();
        }else{
            id = 'id='+id;
        }
        if(id === ''){
            popup.alert('请选择要操作的数据'); return false;
        }
        var serialize = {
            name    : name,
            value   : value
        };
        serialize[param] = token;
        serialize =  $.param(serialize) + '&' + id;
        if ( _this.hasClass('confirm') ) {
            var message = _this.attr('data-alert');
            if(message==='' || message===null || message=== undefined ) {message =  '确认要执行该操作吗?';}
            parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                ajax('post',Url,serialize,function(ret){
                    if(ret.status === 1 || ret.status === true){
                      if(icon === undefined){
                          if(value<=0){
                              parent.layer.msg('已'+text, {icon: 5,time:500},function () {
                                  locationUrl(window.location.href);
                              }); //禁止
                          }else{
                              parent.layer.msg('已'+text, {icon: 1,time:500},function () {
                                  locationUrl(window.location.href);
                              }); //启用

                          }
                      }else{
                          parent.layer.msg('已'+text, {icon: icon,time:500},function () {
                              locationUrl(window.location.href);
                          });
                      }

                    }else{
                        if(ret.url!=='' && ret.url!==null && ret.url!==undefined){
                            popup.error(ret.message,ret.url);
                        }else{
                            popup.alert(ret.message);
                        }
                    }
                });
                parent.layer.close(index);
                return false;
            });
            return false;
        }else{

            ajax('post',Url,serialize,function(ret){
                if(ret.status === 1 || ret.status === true){
                    if(icon === undefined){
                        if(value<=0){
                        parent.layer.msg('已'+text, {icon: 5,time:500},function () {
                            locationUrl(window.location.href);
                        }); //禁止
                        }else{

                            parent.layer.msg('已'+text, {icon: 1,time:500},function () {
                                locationUrl(window.location.href);
                            }); //启用
                        }
                    }else{
                        parent.layer.msg('已'+text, {icon: icon,time:500},function () {
                            locationUrl(window.location.href);
                        });
                    }
                }else{
                    if(ret.url!=='' && ret.url!==null && ret.url!==undefined){
                        popup.error(ret.message,ret.url);
                    }else{
                        popup.alert(ret.message);
                    }
                }
            });
            return false;
        }

    });
    /**
     * 删除
     */
    $('.ajax-delete').click(function () {
        var text,id,param,token,Url,_form,del;
        var _this   = $(this);
        del         = false;

        text = _this.attr('data-text');
        if( text === undefined ){
            text        = '删除';
        }
        Url         = _this.attr('data-url');
        if( Url === undefined ){
            Url     = _this.attr('href');
        }
        id          = _this.attr('data-id');
        if( id === undefined ){
            _form   = _this.attr('data-form');
            id      = $('.'+_form).serialize();
        }else{
            id      = 'id=' + id;
            del         = true;
        }
        if(id === ''){
            popup.alert('请选择要操作的数据');
            return false;
        }
        var serialize    = {

        };
        param     = $("meta[name=csrf-param]").attr("content");
        token     = $("meta[name=csrf-token]").attr("content");
        serialize[param] = token;
        serialize =  $.param(serialize) + '&' + id;
        if ( _this.hasClass('confirm') ) {
            var message = _this.attr('data-alert');
            if(message==='' || message===null || message=== undefined ) {message =  '确认要执行该操作吗?';}
            parent.layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                ajax('post',Url,serialize,function(ret){
                    if(ret.status === 1 || ret.status === true){
                        parent.layer.msg('已'+text,{icon: 1,time:500}, function(){
                            if(del === true){
                                _this.parents('tr').remove();
                            }else{
                                locationUrl(ret.url);
                            }
                        });

                    }else{
                        if(ret.url!=='' && ret.url!==null && ret.url!==undefined){
                            popup.error(ret.message,ret.url);
                        }else{
                            popup.alert(ret.message);
                        }
                    }
                });
                parent.layer.close(index);
                return false;
            });
            return false;
        }else{

            ajax('post',Url,serialize,function(ret){
                if(ret.status === 1 || ret.status === true){
                    popup.success('已'+text,ret.url);
                }else{
                    if(ret.url!=='' && ret.url!==null && ret.url!==undefined){
                        popup.error(ret.message,ret.url);
                    }else{
                        popup.alert(ret.message);
                    }
                }
            });
            return false;
        }
    });

    /**
     *
     */
    $('.ajax-sort-submit').change(function (ret) {
        var _this,id,name,value,param,token,serialize,Url;
        _this   = $(this);
        name    = _this.attr('name');
        id      = _this.attr('data-id');
        value   = _this.val();
        Url     = _this.attr('data-url');
        serialize    = {
            id      : id,
            name    : name,
            value   : value,
        };
        param     = $("meta[name=csrf-param]").attr("content");
        token     = $("meta[name=csrf-token]").attr("content");
        serialize[param] = token;
        ajax('post',Url,serialize,function (ret) {
            if(ret.status ===1 || ret.status ===true){

            }else{
                parent.layer.msg(ret.message, function(){

                });
            }
        });
    });
    /**
     * 弹出
     */
    $('.ajax-iframe-popup').click(function () {
       var _this,iframe,obj,Url;
       _this    = $(this); //{width: '750px', height: '450px', title: '添加公众号'}
       Url      = _this.attr('href');
       iframe   = _this.attr('data-iframe');
       if( iframe === undefined || iframe === ''){
           obj = {};
       }else{
           obj      = eval("(" + iframe + ")");
       }
        if( obj.title === undefined ){
            obj.title = '标题';
        }
        if( obj.skin === undefined ){
            obj.skin  = 'win-popup';
        }
        if( obj.width === undefined ){
            obj.width = '750px';
        }
        if( obj.height === undefined ){
            obj.height = '450px';
        }
        if( obj.scrollbar === undefined ){
            obj.scrollbar = 'no';
        }
        if( obj.btn === undefined ){
            obj.btn = ['确定', '关闭'];
        }
        if( obj.btn === undefined ){
            obj.shadeClose = false;
        }
        parent.layer.open({
            title:obj.title,
            skin: obj.skin,//'layui-layer-library',
            //id: 'layer_layuipro_win-post',
            shade: 0.8,
            type: 2,
            anim:1,
            shadeClose:obj.shadeClose,
            btn: obj.btn,
            btn2: function(index){
                parent.layer.closeAll(index);
            },
            btnAlign: 'r',
            scrollbar:false,
            offset: 'auto',
            area: [obj.width, obj.height],
            content: [Url , obj.scrollbar] ,//, 'no' Yes
            yes: function(index, layero){
                    //console.log(); //layui-layer-iframe1 layui-layer-iframe4

                try{

                    var frameId= layero[0].getElementsByTagName("iframe")[0].id;
                    parent.document.getElementById(frameId).contentWindow.mySubmit();

                }
                catch(err)
                {
                    parent.layer.close(index);
                    //在此处理错误
                }
                //layer.close(index); //如果设定了yes回调，需进行手工关闭

                return false;
            }
       });

       return false;
    });

});

layui.use(['table'], function(){
    var form = layui.form;
    form.on('checkbox(check-all)', function (data) {
        var child = $(data.elem).parents('table').find('tbody .ids');
        child.each(function(index, item){
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });
    //监听指定开关
    form.on('switch(switchShangeStatus)', function(data){
        var _this = $(this);
        var text  = _this.attr('lay-text');
        var attr  = text.split('|');
        var Url   = _this.attr('data-url');
        var id    = _this.attr('data-id');
        var name  = _this.attr('name');
        var value,param,token;
        param     = $("meta[name=csrf-param]").attr("content");
        token     = $("meta[name=csrf-token]").attr("content");
        var _checked = this.checked;
        if( _checked ){
            value = _this.attr('data-open'); //启用
        }else{
            value = _this.attr('data-close'); //禁止
        }
        var serialize = {
            id      : id,
            name    : name,
            value   : value
        };
        serialize[param] = token;
        //console.log(serialize);
        ajax('post',Url,serialize,function (ret) {
            if(ret.status === 1 || ret.status === true){
                if( _checked ){
                    parent.layer.msg('已'+attr[1], {icon: 1}); //启用
                }else{
                    parent.layer.msg('已'+attr[0], {icon: 5}); //禁止
                }
            }else{
                if(_checked){ //
                    _this.prop("checked",false);
                }else{
                    _this.prop("checked",true);
                }
                form.render('checkbox');
                parent.layer.msg(ret.message, {icon: 2});
            }
        });
    });

});