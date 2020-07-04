var csrfParam   = $("meta[name=csrf-param]").attr("content");
var csrfToken   = $("meta[name=csrf-token]").attr("content");
/**
 * 存放对 msui 的 config，需在 zepto 之后 msui 之前加载
 *
 * Created by bd on 15/12/21.
 */
$.config = {
    // 路由功能开关过滤器，返回 false 表示当前点击链接不使用路由
    routerFilter: function($link) {
        // 某个区域的 a 链接不想使用路由功能
        if ($link.is('.disable-router a')) {
            return false;
        }

        return true;
    }
};

function ajax(Method,Url,data,callBack) {
    data[csrfParam] = csrfToken;
    $.ajax({
        type:Method,
        url: Url,
        data: data,
        dataType: 'json',
        timeout: 300,
        async: true, //所有请求均为异步。如果需要发送同步请求
        cache: false, //浏览器是否应该被允许缓存GET响应。
        beforeSend:function () {
            //请求发出前调用
            $.showPreloader();
        },
        complete:function () {
            //请求完成时调用，无论请求失败或成功。
            $.hidePreloader();
        },
        success: callBack,
        error: function () {
            $.toast('Ajax error!');
        }
    });
}