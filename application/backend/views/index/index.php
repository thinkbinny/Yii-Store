<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';

$css = <<<CSS
        .layui-card {border:1px solid #f2f2f2;border-radius:5px;}
        .icon {margin-right:10px;color:#1aa094;}
        .icon-cray {color:#ffb800!important;}
        .icon-blue {color:#1e9fff!important;}
        .icon-tip {color:#ff5722!important;}
        .layuimini-qiuck-module {text-align:center;margin-top: 10px}
        .layuimini-qiuck-module a i {display:inline-block;width:100%;height:60px;line-height:60px;text-align:center;border-radius:2px;font-size:30px;background-color:#F8F8F8;color:#333;transition:all .3s;-webkit-transition:all .3s;}
        .layuimini-qiuck-module a cite {position:relative;top:2px;display:block;color:#666;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;font-size:14px;}
        .welcome-module {width:100%;height:210px;}
        .panel {margin-bottom: 0px;background-color:#fff;border:1px solid transparent;border-radius:3px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}
        .panel-body {padding:10px}
        .panel-title {margin-top:0;margin-bottom:0;font-size:12px;color:inherit}
        .panel-title h5{margin-top:0;margin-bottom:0;font-size: inherit;}
        .panel-content{line-height:24px;}
        .panel-content .no-margins{font-size: inherit;margin-bottom:0;margin-top:8px;font-weight: 500;font-size: 28px;}
        .label {display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em;margin-top: .3em;}
        .layui-red {color:red}
        .main_btn > p {height:40px;}
        .layui-bg-number {background-color:#F8F8F8;}
        .layuimini-notice:hover {background:#f6f6f6;}
        .layuimini-notice {padding:7px 16px;clear:both;font-size:12px !important;cursor:pointer;position:relative;transition:background 0.2s ease-in-out;}
        .layuimini-notice-title,.layuimini-notice-label {
            padding-right: 70px !important;text-overflow:ellipsis!important;overflow:hidden!important;white-space:nowrap!important;}
        .layuimini-notice-title {line-height:28px;font-size:14px;}
        .layuimini-notice-extra {position:absolute;top:50%;margin-top:-8px;right:16px;display:inline-block;height:16px;color:#999;}
CSS;
$this->registerCss($css);


$js = <<<JS
layui.use(['layer', 'layuimini','echarts'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            layuimini = layui.layuimini,
            echarts = layui.echarts;

        /**
         * 查看公告信息
         **/
       /* $('body').on('click', '.layuimini-notice', function () {
            var title = $(this).children('.layuimini-notice-title').text(),
                noticeTime = $(this).children('.layuimini-notice-extra').text(),
                content = $(this).children('.layuimini-notice-content').html();
            var html = '<div style="padding:15px 20px; text-align:justify; line-height: 22px;border-bottom:1px solid #e2e2e2;background-color: #2f4056;color: #ffffff">\n' +
                '<div style="text-align: center;margin-bottom: 20px;font-weight: bold;border-bottom:1px solid #718fb5;padding-bottom: 5px"><h4 class="text-danger">' + title + '</h4></div>\n' +
                '<div style="font-size: 12px">' + content + '</div>\n' +
                '</div>\n';
            parent.layer.open({
                type: 1,
                title: '系统公告'+'<span style="float: right;right: 1px;font-size: 12px;color: #b1b3b9;margin-top: 1px">'+noticeTime+'</span>',
                area: '300px;',
                shade: 0.8,
                id: 'layuimini-notice',
                btn: ['查看', '取消'],
                btnAlign: 'c',
                moveType: 1,
                content:html,
                success: function (layero) {
                    var btn = layero.find('.layui-layer-btn');
                    btn.find('.layui-layer-btn0').attr({
                        href: '',
                        target: '_blank'
                    });
                }
            });
        });*/

        /**
         * 报表功能
         */
        var echartsRecords = echarts.init(document.getElementById('echarts-records'), 'walden');
        var optionRecords = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['成交量','成交额']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['周一','周二','周三','周四','周五','周六','周日']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'成交量',
                    type:'line',
                    stack: '总量',
                    data:[120, 132, 101, 134, 90, 230, 210]
                },
                {
                    name:'成交额',
                    type:'line',
                    stack: '总量',
                    data:[220, 182, 191, 234, 290, 330, 310]
                },
               
            ]
        };
        echartsRecords.setOption(optionRecords);
        // echarts 窗口缩放自适应
        window.onresize = function(){
            echartsRecords.resize();
        }

    });
JS;

$this->registerJs($js,\yii\web\View::POS_END);
?>
<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fas fa-exclamation-triangle icon"></i>数据统计</div>
                            <div class="layui-card-body">
                                <div class="welcome-module">
                                    <div class="layui-row layui-col-space10">
                                        <div class="layui-col-xs6">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-blue">实时</span>
                                                        <h5>用户统计</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?=$statistics['user']?></h1>
                                                        <small>当前用户总记录数</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-xs6">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-cyan">实时</span>
                                                        <h5>商品统计</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?=$statistics['goods']?></h1>
                                                        <small>当前商品总记录数</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-xs6">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-orange">实时</span>
                                                        <h5>订单统计</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?=$statistics['order']?></h1>
                                                        <small>已付款订单总数量</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-xs6">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-green">实时</span>
                                                        <h5>等待发货</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?=$statistics['order_pay']?></h1>
                                                        <small>等待发货订单总数量</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-credit-card icon icon-blue"></i>快捷入口</div>
                            <div class="layui-card-body">
                                <div class="welcome-module">
                                    <div class="layui-row layui-col-space10 layuimini-qiuck">
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['menu/index'])?>" data-iframe-tab="<?=Url::to(['menu/index'])?>" data-title="菜单管理" data-icon="fa fa-window-maximize">
                                                <i class="fa fa-window-maximize"></i>
                                                <cite>菜单管理</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['config/setup','id'=>1]);?>" data-iframe-tab="<?=Url::to(['config/setup','id'=>1]);?>" data-title="系统设置" data-icon="fas fa-cogs">
                                                <i class="fas fa-cogs"></i>
                                                <cite>系统设置</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['store/index'])?>" data-iframe-tab="<?=Url::to(['store/index'])?>" data-title="门店管理" data-icon="fas fa-store-alt">
                                                <i class="fas fa-store-alt"></i>
                                                <cite>门店管理</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['store/index'])?>" data-iframe-tab="<?=Url::to(['store/index'])?>" data-title="店员管理" data-icon="fas fa-user-alt">
                                                <i class="fas fa-user-alt"></i>
                                                <cite>店员管理</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['goods/index'])?>" data-iframe-tab="<?=Url::to(['goods/index'])?>" data-title="商品管理" data-icon="fas fa-store">
                                                <i class="fas fa-store"></i>
                                                <cite>商品管理</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['prom-seckill/index'])?>" data-iframe-tab="<?=Url::to(['prom-seckill/index'])?>" data-title="抢购管理" data-icon="fas fa-shopping-bag">
                                                <i class="fas fa-shopping-bag"></i>
                                                <cite>抢购管理</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['coupon/index'])?>" data-iframe-tab="<?=Url::to(['coupon/index'])?>" data-title="优惠券管理" data-icon="fas fa-puzzle-piece">
                                                <i class="fas fa-puzzle-piece"></i>
                                                <cite>优惠券管理</cite>
                                            </a>
                                        </div>
                                        <div class="layui-col-xs3 layuimini-qiuck-module">
                                            <a href="<?=Url::to(['order/index'])?>" data-iframe-tab="<?=Url::to(['order/index'])?>" data-title="订单管理" data-icon="fas fa-gift">
                                                <i class="fas fa-gift"></i>
                                                <cite>订单管理</cite>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-line-chart icon"></i>报表统计</div>
                            <div class="layui-card-body">
                                <div id="echarts-records" style="width: 100%;min-height:500px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md4">

                <div class="layui-card">
                    <div class="layui-card-header"><i class="fa fa-bullhorn icon icon-tip"></i>系统公告</div>
                    <div class="layui-card-body layui-text">
                        <div class="layuimini-notice">
                            <div class="layuimini-notice-title">修改选项卡样式</div>
                            <div class="layuimini-notice-extra">2019-07-11 23:06</div>
                            <div class="layuimini-notice-content layui-hide">
                                界面足够简洁清爽。<br>
                                一个接口几行代码而已直接初始化整个框架，无需复杂操作。<br>
                                支持多tab，可以打开多窗口。<br>
                                支持无限级菜单和对font-awesome图标库的完美支持。<br>
                                失效以及报错菜单无法直接打开，并给出弹出层提示完美的线上用户体验。<br>
                                url地址hash定位，可以清楚看到当前tab的地址信息。<br>
                                刷新页面会保留当前的窗口，并且会定位当前窗口对应左侧菜单栏。<br>
                                移动端的友好支持。<br>
                            </div>
                        </div>
                        <div class="layuimini-notice">
                            <div class="layuimini-notice-title">新增系统404模板</div>
                            <div class="layuimini-notice-extra">2019-07-11 12:57</div>
                            <div class="layuimini-notice-content layui-hide">
                                界面足够简洁清爽。<br>
                                一个接口几行代码而已直接初始化整个框架，无需复杂操作。<br>
                                支持多tab，可以打开多窗口。<br>
                                支持无限级菜单和对font-awesome图标库的完美支持。<br>
                                失效以及报错菜单无法直接打开，并给出弹出层提示完美的线上用户体验。<br>
                                url地址hash定位，可以清楚看到当前tab的地址信息。<br>
                                刷新页面会保留当前的窗口，并且会定位当前窗口对应左侧菜单栏。<br>
                                移动端的友好支持。<br>
                            </div>
                        </div>
                        <div class="layuimini-notice">
                            <div class="layuimini-notice-title">新增treetable插件和菜单管理样式</div>
                            <div class="layuimini-notice-extra">2019-07-05 14:28</div>
                            <div class="layuimini-notice-content layui-hide">
                                界面足够简洁清爽。<br>
                                一个接口几行代码而已直接初始化整个框架，无需复杂操作。<br>
                                支持多tab，可以打开多窗口。<br>
                                支持无限级菜单和对font-awesome图标库的完美支持。<br>
                                失效以及报错菜单无法直接打开，并给出弹出层提示完美的线上用户体验。<br>
                                url地址hash定位，可以清楚看到当前tab的地址信息。<br>
                                刷新页面会保留当前的窗口，并且会定位当前窗口对应左侧菜单栏。<br>
                                移动端的友好支持。<br>
                            </div>
                        </div>
                        <div class="layuimini-notice">
                            <div class="layuimini-notice-title">修改logo缩放问题</div>
                            <div class="layuimini-notice-extra">2019-07-04 11:02</div>
                            <div class="layuimini-notice-content layui-hide">
                                界面足够简洁清爽。<br>
                                一个接口几行代码而已直接初始化整个框架，无需复杂操作。<br>
                                支持多tab，可以打开多窗口。<br>
                                支持无限级菜单和对font-awesome图标库的完美支持。<br>
                                失效以及报错菜单无法直接打开，并给出弹出层提示完美的线上用户体验。<br>
                                url地址hash定位，可以清楚看到当前tab的地址信息。<br>
                                刷新页面会保留当前的窗口，并且会定位当前窗口对应左侧菜单栏。<br>
                                移动端的友好支持。<br>
                            </div>
                        </div>
                        <div class="layuimini-notice">
                            <div class="layuimini-notice-title">修复左侧菜单缩放tab无法移动</div>
                            <div class="layuimini-notice-extra">2019-06-17 11:55</div>
                            <div class="layuimini-notice-content layui-hide">
                                界面足够简洁清爽。<br>
                                一个接口几行代码而已直接初始化整个框架，无需复杂操作。<br>
                                支持多tab，可以打开多窗口。<br>
                                支持无限级菜单和对font-awesome图标库的完美支持。<br>
                                失效以及报错菜单无法直接打开，并给出弹出层提示完美的线上用户体验。<br>
                                url地址hash定位，可以清楚看到当前tab的地址信息。<br>
                                刷新页面会保留当前的窗口，并且会定位当前窗口对应左侧菜单栏。<br>
                                移动端的友好支持。<br>
                            </div>
                        </div>
                        <div class="layuimini-notice">
                            <div class="layuimini-notice-title">修复多模块菜单栏展开有问题</div>
                            <div class="layuimini-notice-extra">2019-06-13 14:53</div>
                            <div class="layuimini-notice-content layui-hide">
                                界面足够简洁清爽。<br>
                                一个接口几行代码而已直接初始化整个框架，无需复杂操作。<br>
                                支持多tab，可以打开多窗口。<br>
                                支持无限级菜单和对font-awesome图标库的完美支持。<br>
                                失效以及报错菜单无法直接打开，并给出弹出层提示完美的线上用户体验。<br>
                                url地址hash定位，可以清楚看到当前tab的地址信息。<br>
                                刷新页面会保留当前的窗口，并且会定位当前窗口对应左侧菜单栏。<br>
                                移动端的友好支持。<br>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-card">
                    <div class="layui-card-header"><i class="fa fa-fire icon"></i>版本信息</div>
                    <div class="layui-card-body layui-text">
                        <table class="layui-table">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>框架名称</td>
                                <td>
                                    layuimini
                                </td>
                            </tr>
                            <tr>
                                <td>当前版本</td>
                                <td>v1.0.4</td>
                            </tr>
                            <tr>
                                <td>主要特色</td>
                                <td>零门槛 / 响应式 / 清爽 / 极简</td>
                            </tr>
                            <tr>
                                <td>演示地址</td>
                                <td>
                                    iframe版：<a href="http://layuimini.99php.cn/" target="_blank">点击查看</a><br>
                                    单页版：<a href="http://layuimini-onepage.99php.cn" target="_blank">点击查看</a>
                                </td>
                            </tr>
                            <tr>
                                <td>下载地址</td>
                                <td>
                                    iframe版：<a href="https://github.com/zhongshaofa/layuimini" target="_blank">github</a> / <a href="https://gitee.com/zhongshaofa/layuimini" target="_blank">gitee</a><br>
                                    单页版：<a href="https://github.com/zhongshaofa/layuimini/tree/onepage" target="_blank">github</a> / <a href="https://gitee.com/zhongshaofa/layuimini/tree/onepage" target="_blank">gitee</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Gitee</td>
                                <td style="padding-bottom: 0;">
                                    <div class="layui-btn-container">
                                        <a href="https://gitee.com/zhongshaofa/layuimini" target="_blank" style="margin-right: 15px"><img src="https://gitee.com/zhongshaofa/layuimini/badge/star.svg?theme=dark" alt="star"></a>
                                        <a href="https://gitee.com/zhongshaofa/layuimini" target="_blank"><img src="https://gitee.com/zhongshaofa/layuimini/badge/fork.svg?theme=dark" alt="fork"></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Github</td>
                                <td style="padding-bottom: 0;">
                                    <div class="layui-btn-container">
                                        <iframe src="https://ghbtns.com/github-btn.html?user=zhongshaofa&repo=layuimini&type=star&count=true" frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
                                        <iframe src="https://ghbtns.com/github-btn.html?user=zhongshaofa&repo=layuimini&type=fork&count=true" frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="layui-card">
                    <div class="layui-card-header"><i class="fa fa-paper-plane-o icon"></i>作者心语</div>
                    <div class="layui-card-body layui-text layadmin-text">
                        <p>本模板基于layui2.5.4以及font-awesome-4.7.0进行实现。layui开发文档地址：<a class="layui-btn layui-btn-xs layui-btn-danger" target="_blank" href="http://www.layui.com/doc">layui文档</a></p>
                        <p>技术交流QQ群（561838086）：<a target="_blank" href="https://jq.qq.com/?_wv=1027&k=5JRGVfe"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="layuimini" title="layuimini"></a>（加群请备注来源：如gitee、github、官网等）</p>
                        <p>喜欢此后台模板的可以给我的GitHub和Gitee加个Star支持一下</p>
                        <p class="layui-red">备注：此后台框架永久开源，但请勿进行出售或者上传到任何素材网站，否则将追究相应的责任。</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
