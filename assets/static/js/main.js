$(function(){
    //ajax post submit请求
    $('.ajax-post').click(function(){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var message = $(this).attr('data-alert');
        if(message=='' || message==null || message=='undefined') message =  '确认要执行该操作吗?';
        var nead_confirm=false;
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('data-url'))  || (target = $(this).attr('href'))){
            form = $('.'+target_form);query = form.serialize();
            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)==undefined){
                //链接提交

                query  = {_csrf:csrfToken,t:Math.random()};
                target = $(this).attr('href');
                if ( $(this).hasClass('confirm') ) {
                    layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        popup.ajax('post',target,query);
                        layer.close(index);
                        return false
                    });
                    return false;
                }
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if($(this).attr('data-url') !== undefined){
                    target = $(this).attr('data-url');
                }else{
                    target = form.get(0).action;
                }
                query = form.serialize();
                if ( $(this).hasClass('confirm') ) {
                    layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        popup.ajax('post',target,query);
                        layer.close(index);
                        return false
                    });
                    //popup.ajaxPostConfirm(target,query,that);
                    return false;
                }
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                });
                query = form.serialize();
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        popup.ajax('post',target,query);
                        layer.close(index);
                        return false
                    });
                    //popup.ajaxPostConfirm(target,query,that);
                    return false;
                }
            }else{
                query = form.find('input,select,textarea').serialize();
                if ( $(this).hasClass('confirm') ) {
                    //popup.ajaxPostConfirm(target,query,that);
                    layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                        popup.ajax('post',target,query);
                        layer.close(index);
                        return false
                    });
                    return false;
                }
            }
            popup.ajax('post',target,query);
            return false;
        }
        return false;
    });
//ajax get请求
    $('.ajax-get').click(function(){
        var target,dataUrl,dataHref;
        var that = this;
        dataUrl = $(this).attr('data-url');
        dataHref = $(this).attr('href');

        if(dataUrl!='' && dataUrl!=undefined && dataUrl!=null){
            target = dataUrl;
        }else if(dataHref!='' && dataHref!=undefined && dataHref!=null){
            target = dataHref;
        }else{
            popup.alert('参数出错！');
        }
        var t = Math.random();
        if ( $(this).hasClass('confirm') ) {
            var message = $(this).attr('data-alert');
            if(message=='' || message==null || message=='undefined') message =  '确认要执行该操作吗?';
            layer.confirm(message, {icon: 3, title:'提示'}, function(index){
                popup.ajax('get',target,{t:t});
                layer.close(index);
                return false
            });
            return false;
        }
        popup.ajax('get',target,{t:t});
        return false;
    });



});

var popup = {
    alert:function(message,width){
        layer.msg(message, {icon: 0,scrollbar: false,offset: ['45%','45%'],area: ['auto', '66px;']});
    },
    success:function (message,Url) {
        //success
        layer.alert(message, {icon: 6,scrollbar: false},function (index) {
            if(Url!='' && Url!=null && Url!='undefined'){
                window.location.href=Url;
            }else{
                window.location.reload();
            }
        });

    },
    error:function (message,Url) {
        layer.alert(message, {icon: 2,scrollbar: false},function (index) {
            if(Url!='' && Url!=null && Url!='undefined'){
                window.location.href=Url;
            }else{
                window.location.reload();
            }
        });
    },
    loader:function(){
        layer.load(0,{shade: [0.2, '#000'],scrollbar: false,area: ['60px', '30px']});
    },
    noLoader:function(index){
        layer.closeAll('loading');
    },
    ajaxfrom:function(Method,Url,data,callBack){
        $.ajax({
            type:Method,
            url:Url,
            data:data,
            beforeSend: function () {
                popup.loader();
            },
            complete: function () {
                popup.noLoader();
            },
            success: callBack,
            error: function (e, jqxhr, settings, exception) {
                popup.alert("服务器异常！");
            }
        })
    },
    ajax:function (Method,Url,data) {
        popup.ajaxfrom(Method,Url,data,function (data) {
            if(data.status==true){
                popup.success(data.message,data.url);
            }else{
                if(data.url!='' && data.url!=null && data.url!='undefined'){
                    popup.error(data.message,data.url);
                }else{
                    popup.alert(data.message);
                }
            }
        })
    }
};
