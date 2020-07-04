<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use assets\backendAsset as AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
$this->registerCssFile(Yii::$app->params['assetsUrl'].'/css/style.css',[ 'depends' => 'assets\backendAsset']);

$model = new \backend\models\Menu();
$menu  = $model->getFindModel();
$url        =   Yii::$app->requestedRoute;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<?php

$js = <<<JS
layui.use(['layer', 'layuimini','echarts'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            layuimini = layui.layuimini,
            echarts = layui.echarts;
    });

$('.refresh').click(function() {
  window.location.reload();
  
   parent.layer.msg('刷新成功', {icon: 1,scrollbar: false,offset: ['45%','45%'],area: ['auto', '66px;']});
})

JS;

$this->registerJs($js,\yii\web\View::POS_END);


?>
<body class="layui-layout-body layuimini-all">
<?php $this->beginBody() ?>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header header">
        <div class="layui-logo">
            <a href="<?=Url::to(['index/index'])?>">
                <img src="<?=$menu['logoInfo']['image']?>" alt="logo">
                <h1><?=$menu['logoInfo']['title']?></h1>
            </a></div>
        <a href="javascript:;">
            <div class="layuimini-tool"><i title="展开" class="fa fa-outdent" data-side-fold="1"></i></div>
        </a>
        <?php
        ?>
          <?php
            $menuHtml   = '';
            $childHtml  = '';
            $controller = Yii::$app->controller->id;
            if(isset($this->params['thisUrl'])){
                $thisUrl    = $this->params['thisUrl'];
                if(strstr($thisUrl, '/') === false){
                    $thisUrl    = $controller.'/index';
                }
            }else{
                $thisUrl    = $controller.'/index';
            }

            foreach ($menu['menuInfo'] as $key=> $val): //layui-this
                $li = '';
                $layuiChildThis = 'layui-hide';
                $layuiMenuThis  = '';
                foreach($val['child'] as $vo):
                    $child = '';
                    $layuiNavItem = '';
                    if(isset($vo['child'])):
                        $dd = '';
                        foreach ($vo['child'] as $v):
                            $layuiThis = '';
                            if($v['url'] == $thisUrl){
                                $layuiThis      = 'layui-this';
                                $layuiNavItem   = 'layui-nav-itemed';
                                $layuiChildThis = 'layui-this';
                                $layuiMenuThis  = 'layui-this';
                            }
                            $text = Html::tag('i','',['class'=>$v['icon']]) .' '. Html::tag('span',$v['title'],['class'=>'layui-left-nav']);
                            $text = Html::a($text,$v['href'],['class'=>'layui-menu-tips']);
                            $dd   .= Html::tag('dd',$text,['class'=>$layuiThis]);
                        endforeach;
                        $child = Html::tag('dl',$dd,['class'=>'layui-nav-child']);
                    endif;
                    $text = Html::tag('i','',['class'=>$vo['icon']]).' ';
                    $text = $text . Html::tag('span',$vo['title'],['class'=>'layui-left-nav']);
                    $text = $text . Html::tag('span','',['class'=>'layui-nav-more']);
                    $href = Html::a($text,'javascript:;',['class'=>'layui-menu-tips']);
                    $li .= Html::tag('li',$href . $child,['class'=>'layui-nav-item '.$layuiNavItem]);//layui-nav-itemed
                endforeach;
                $current = 'layui-hide';
                if($url == 'index/index' && $key==0){
                    $current = 'layui-this';
                }else{
                    $current = $layuiChildThis;
                }

                $childHtml .= Html::tag('ul',$li,['id'=>$val['id'],'class'=>'layui-nav layui-nav-tree layui-left-nav-tree '.$current]);

                //导航
                $current = '';
                if($url == 'index/index' && $key==0){
                    $current = 'layui-this';
                }else{
                    $current = $layuiMenuThis;
                }
                $text = Html::tag('i','',['class'=>$val['icon']]) .' '. $val['title'];
                $text = Html::a($text,'javascript:;');
                $menuHtml .= Html::tag('li',$text,[
                      'data-menu' => $val['id'],
                      'id' => $val['id'].'HeaderId',
                      'class' => 'layui-nav-item '.$current,
                ]);
            endforeach;

           echo Html::tag('ul',$menuHtml,['class'=>'layui-nav layui-layout-left layui-header-menu layui-header-pc-menu mobile layui-hide-xs'])
          ?>

        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="/" title="首页"><i class="fas fa-home"></i></a>
            </li>
            <li class="layui-nav-item">
                <a class="refresh" href="javascript:;" data-refresh="刷新"><i class="fas fa-sync-alt"></i></a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;" data-clear="清理" class="layuimini-clear"><i class="far fa-trash-alt"></i></a>
            </li>
            <li class="layui-nav-item layuimini-setting">
                <?php
                $username  = Yii::$app->user->getIdentity();
                echo Html::a($username->username,'javascript:;');
                ?>
                <a href="javascript:;">admin</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="<?=Url::to(['admin/editinfo'])?>">基本资料</a>
                    </dd>
                    <dd >
                        <a href="<?=Url::to(['admin/resetpwd'])?>">修改密码</a>
                    </dd>
                    <dd >
                        <a href="<?=Url::to(['public/logout'])?>" class="login-out">退出登录</a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item layuimini-select-bgcolor mobile layui-hide-xs">
                <a href="javascript:;" data-bgcolor="配色方案"><i class="fa fa-ellipsis-v"></i></a>
            </li>
        </ul>
    </div>
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll layui-left-menu">

            <?php

              echo $childHtml;
            ?>

        </div>
    </div>

    <div class="layui-body">
        <div class="layui-card layuimini-page-header layui-hide-xs">

            <?php
            echo Breadcrumbs::widget([
                'tag'=>'div','options'=>['class' => 'layui-breadcrumb'],
                'itemTemplate'=>'{link}<span lay-separator="">/</span>',//<span>/</span>
                'activeItemTemplate'=>'<cite>{link}</cite>',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);

            ?>

            <div class="layui-breadcrumb" id="layuimini-page-header" style="visibility: visible;display: none">
                <a lay-href="" href="/">主页</a><span lay-separator="">/</span>
                <a><cite>组件管理</cite></a><span lay-separator="">/</span>
                <a><cite>图标选择</cite></a>
            </div>
        </div>
        <div class="layuimini-content-page">
            <?= $content?>
        </div>
    </div>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
