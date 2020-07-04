<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$assets_url  = Yii::$app->params['assetsUrl'];
$this->title = '管理员管理';
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
var Url = '".\yii\helpers\Url::to(['google-authenticator'])."';
 $('.GoogleAuthenticator').click(function(){  
     var uid      = $(this).attr('data-uid');
     var pic_url  = $(this).attr('data-url');
     var username = $(this).attr('data-username');
     var type     = $(this).attr('data-type');
     var typeText = '';
     if(type=='open'){       
        typeText = ['立即开启'];
     }else{
        typeText = ['重置验证码', '关闭验证'];        
     }

     layer.open({
        title: username+'认证-【谷歌认证器扫一扫】' 
        ,type: 1        
        ,area: '400px;'
        ,shadeClose:true
        ,shade: 0.8
        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
        ,btn: typeText
        ,btnAlign: 'c'
        ,moveType: 1 //拖拽模式，0或者1
        ,content: '<div style=\"padding: 10px 50px;width: 400px;height: 320px; background-color: #393D49; color: #fff; font-weight: 300;\"><img style=\"width: 100%;\" src=\"'+pic_url+'\"></div>'
        ,success: function(layero){
          
        } 
        ,yes: function(index, layero){
             var param     = $(\"meta[name=csrf-param]\").attr(\"content\");
             var token     = $(\"meta[name=csrf-token]\").attr(\"content\");
             var data = {uid:uid,type:type};
             data[param] = token;
             ajaxSubmit('post',Url,data);
             layer.close(index)
        } 
        ,btn2: function(index, layero){        
            var param     = $(\"meta[name=csrf-param]\").attr(\"content\");
             var token     = $(\"meta[name=csrf-token]\").attr(\"content\");
             var data = {uid:uid,type:'close'};
             data[param] = token;
             ajaxSubmit('post',Url,data);
             layer.close(index)
        }
      });
  })
  
  $('.download').click(function(){
        var pic_url  = $(this).attr('data-pic_url');
        layer.open({
        type: 1
        ,title: '扫一扫下载 GoogleAuthenticator' 
        ,closeBtn: false
        ,area: '400px;'
        ,shade: 0.8
        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
        ,btn: [ '关闭']
        ,btnAlign: 'c'
        ,moveType: 1 //拖拽模式，0或者1
        ,content: '<div style=\"padding: 10px 50px;width: 400px;height: 320px; background-color: #393D49; color: #fff; font-weight: 300;\"><img style=\"width: 100%;\" src=\"'+pic_url+'\"></div>'
        ,success: function(layero){
          
        }
      });
       return false;
  });
  
");
// https://itunes.apple.com/cn/app/google-authenticator/id388497605
//http://www.appchina.com/app/com.google.android.apps.authenticator2
?>
<div class="layuimini-container">
    <div class="layuimini-main">
    <?php Pjax::begin(); ?>
    <div style="margin-bottom:10px;" class="alert-danger alert fade in">
         <?php
         if(Yii::$app->params['GoogleAuthenticator']==1) echo '<div style="color: red">系统强行开启 GoogleAuthenticator 认证，请开启谷歌验证器,否则无法登陆。</div>'
         ?>
         温馨提示：<br>如果开启 GoogleAuthenticator 认证,认证后才可以入后台。<br>如果密钥丢失无法进入后台，也无法找回，只能让技术人员重新生成。<br>
         下载地址：IOS系统<a class="download" style="color: #00aaef" data-pic_url="<?=$assets_url?>/images/GoogleAuthenticator/IOS.png" href="javascript:;" target="_blank">下载</a>，
         安卓系统<a class="download" style="color: #00aaef" data-pic_url="<?=$assets_url?>/images/GoogleAuthenticator/Android.png" href="javascript:;" target="_blank">下载</a>.<br>
         此功能只有超级管理员才可以开启。
    </div>
        <?=$this->render('_tab_menu');?>
    <div class="page-toolbar" style="display: none">
        <div class="layui-btn-group">
            <?php
            echo Html::a('&nbsp;添加',['create'],[
                'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
                'data-iframe'   => "{width: '660px', height: '350px', title: '添加管理员'}",
            ]);
            ?>
        </div>
        <div class="page-filter pull-right layui-search-form">

        </div>
    </div>
    <?php
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'options'   =>['width'=>50,],
                'attribute' => 'id',
            ],
            [
                'options'   =>['width'=>110,],
                'attribute' => 'username',
            ],
            [
                'options'   =>['width'=>90,],
                'attribute' => 'realname',
            ],

            [
                'options'   =>['width'=>150,],
                'attribute' => 'last_login_ip',
                'value' => function ($data) {
                    return long2ip($data->last_login_ip);
                }
            ],
            [
                'options'   =>['width'=>170,],
                'attribute' => 'last_login_time',
                'format'    => 'datetime',
            ],

            [
                'attribute' => 'user_role',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::tag('span', $data->getGroup(), ['class' => 'label label-sm label-info']);
                }
            ],
            [
                'options'   =>['width'=>120,],
                'label'     =>'谷歌验证器',
                'format'    => 'raw',
                'value'=>function($data){
                    $list   = $data->GoogleAuthenticator();
                    $url    = \yii\helpers\Url::to(['google-make','uid'=>$data->id]);
                    if($list==false){
                        $html = Html::tag('span','立即开启',['data-type'=>'open','data-uid'=>$data['id'],'data-url'=>$url,'data-username'=>$data['username'],'style'=>'cursor: pointer;','class'=>'label label-sm label-warning GoogleAuthenticator']);
                        return $html;
                    }else{
                        $html  =  Html::tag('span','已开启',['class'=>'label label-sm label-info']);
                        $html .=  Html::tag('span','查看',['data-type'=>'show','data-uid'=>$data['id'],'data-url'=>$url,'data-username'=>$data['username'],'style'=>'cursor: pointer;margin-left: 10px;','class'=>'label label-sm label-danger GoogleAuthenticator']);
                        return $html;
                    }
                }
            ],
            [
                'class'         => 'backend\grid\SwitchColumn',
                'options'       =>['width'=>90,],
                'header'        => '是否显示',
                'attribute'     => 'status',
                'classOptions'  =>['data-open'=>10],
            ],

            [
                'class'     => 'backend\grid\ActionColumn',
                'options'   => ['width'=>190,],
                'header'    => Yii::t('backend', 'Operate'),
                'template'  => '{update} {auth} {delete}',
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '660px', height: '350px',title: '更新角色'}"
                    ],
                    'auth'=>[
                        'class'=>'btn btn-success btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '500px', height: '250px',title: '受权设置'}"
                    ],
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
        </div>
    </div>

<?php
$this->registerJs('
    $(".gridview").on("click", function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
    });
');
?>