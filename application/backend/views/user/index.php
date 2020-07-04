<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户信息';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

  $('.more').click(function(){   
      if($(this).find('.layui-nav-child').hasClass('layui-show')){
        $(this).find('.layui-nav-child').removeClass('layui-show');
      }else{
        $('.more').find('.layui-nav-child').removeClass('layui-show');
        $(this).parent().find('.layui-nav-child').addClass('layui-show'); //
      }
  });
  $('.more .layui-nav-child a').click(function(){ 
        $(this).parents('.layui-nav-child').removeClass('layui-show');
  })
");
$this->registerCss("
    .layui-form .summary{color: #333;}
    .more{display: inline-block;position:relative;}
    .more .layui-nav-child{top:38px;left:-64px;width:120px;}
    .more ul{margin-top: 0;margin-bottom: 20px;}
    
    .more ul:before,.more ul:after {position:absolute;display:block;content:\"\";width:0;height:0;border:8px dashed transparent;z-index:1}}
    .more ul:after, .more ul:before { border-bottom-style:solid;border-width:0 8px 8px }
    .more ul:before {border-bottom-color:#d2d2d2;bottom:0 }
    .more ul:after{border-bottom-color:#fff;bottom:-1px}
    .more ul:after,.more ul:before { right: 10px; top: -16px; pointer-events: none; }
    .more ul:after{top:-15px}
    
   
    .more ul li{position: relative;line-height: inherit;}
    .more ul li a{display: block;padding: 0 20px;color: #333;transition: all .3s;-webkit-transition: all .3s;}
    .more ul li a:hover{background-color:#eee }
");
?>


<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>

    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?
echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {summary} {pager}',
        'pager'=>[
            //'options'=>['class'=>'layui-box layui-laypage layui-laypage-default'],
            'firstPageLabel'=>"第一页",
            'prevPageLabel'=>'上一页',//'Prev',
            'nextPageLabel'=>'下一页',//'Next',
            'lastPageLabel'=>'最后一页',
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'uid',
                'options'=>['width'=>49,],
            ],
            [
                'options'=>['width'=>80,],
                'attribute'   =>  'uid',
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'headimgurl',
                'format'    => [
                    'image',
                    [
                        'width'=>'55',
                        'height'=>'55'
                    ]
                ],
            ],
            [
                'attribute' => 'nickname',
                'format'    => 'raw',


            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'money',
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'integral',
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'grade',
                'value' => function($model){
                    return $model->getGradeText();
                }
            ],
            [
                'options'=>['width'=>90,],
                'attribute'   =>  'cost_money',
            ],
            //'sex',
            //'qq',
            /*[
                'options'=>['width'=>100,],
                'attribute'   =>  'login',
            ],
            [
              'options'     =>  ['width'=>120,],
              //'attribute'   =>  'reg_ip',
              'label'       =>  '注册IP',
              'value'       =>  function($data){
                return long2ip($data->reg_ip);
              }
            ],
            [
                'options'=>['width'=>170,],
                'attribute'   =>  'reg_time',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->reg_time);
                }
            ],
            [
                'options'=>['width'=>120,],
                //'attribute'   =>  'last_login_ip',
                'label'       =>  '最后登陆IP',
                'value'       =>  function($data){
                    return long2ip($data->last_login_ip);
                }
            ],*/
            [
                'options'=>['width'=>170,],
                'attribute'   =>  'last_login_time',

                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->last_login_time);
                }
            ],
            [
                'class'     => 'backend\grid\SwitchColumn',
                'options'   =>['width'=>90,],
                'header'    => '是否开启',
                'attribute' => 'status',
                'field'     => 'uid',
                /*'classOptions'=>[
                        'data-id'=>function(){
                            return 'ff';
                        }
                ],*/

            ],
             [
                 'class' => 'backend\grid\ActionColumn',
                 'options'=>['width'=>225,],
                 'header' => Yii::t('backend', 'Operate'),
                 'template' => '{recharge} {grade} {more}',//字段管理 {update} {delete}
                 'buttons' => [
                     /*'view' => function ($url, $model, $key) {
                         $options = ['class'=>'btn btn-info btn-xs'];
                         $url = \yii\helpers\Url::to(['view','id'=>$model->uid]);
                         return Html::a('<span class="fa fa-eye"></span> 查看', $url, $options);
layui-btn layui-btn-primary layui-btn-sm
                     },*/
                     'recharge' => function ($url, $model, $key) {
                         $options = [
                             'class'=>'layui-btn layui-btn-primary layui-btn-sm ajax-iframe-popup',
                             'style'=>'margin-left:0;',
                             'data-iframe'   => "{width: '550px', height: '480px', title: '充值'}",];
                         $url = \yii\helpers\Url::to(['recharge','id'=>$model->uid]);
                         return Html::a('<span class="fas fa-coins"></span> 充值', $url, $options);

                     },
                     'grade' => function ($url, $model, $key) {
                         $options = [
                             'class'=>'layui-btn layui-btn-primary layui-btn-sm ajax-iframe-popup',
                             'style'=>'margin-left:0;',
                             'data-iframe'   => "{width: '650px', height: '310px', title: '修改会员等级',scrollbar:'Yes',}",
                         ];
                         $url = \yii\helpers\Url::to(['grade','id'=>$model->uid]);
                         return Html::a('<span class="far fa-gem"></span> 等级', $url, $options);

                     },
                    'view' => function ($url, $model, $key) {
                        $options = ['class'=>'layui-btn layui-btn-primary layui-btn-sm','style'=>'margin-left:0;'];
                        $url = \yii\helpers\Url::to(['view','id'=>$model->uid]);
                        return Html::a('更多 <span class="fas fa-caret-down"></span>', $url, $options);

                    },
                    'more' => function ($url, $model, $key) {
                        $url = \yii\helpers\Url::to(['view','id'=>$model->uid]);
                        $item = [
                          Html::a('会员详细',$url,['class'=>'ajax-iframe-popup','data-iframe'=>"{btn:false,scrollbar:'Yes',shadeClose:true,width: '1000px', height: '90%', title: '会员详细'}"]),
                          Html::a('重置密码',['resetpassword','id'=>$model->uid],['class'=>'ajax-iframe-popup','data-iframe'=>"{width: '650px', height: '220px', title: '重置密码'}"]),
                          Html::a('用户订单','javascript:;'),
                          Html::a('余额明细','javascript:;'),
                        ];
                        $Html = Html::ul($item,['class'=>'layui-nav-child layui-anim layui-anim-upbit','encode'=>false,'style'=>'']);// layui-show
                        $options = ['class'=>'layui-btn layui-btn-primary layui-btn-sm','style'=>'margin-left:0;'];
                        $Html = Html::tag('div','更多 <span class="fas fa-caret-down"></span>', $options).$Html;
                        return Html::tag('div',$Html,['class'=>'more']);
                        //return Html::a('更多 <span class="fas fa-caret-down"></span>'.$Html, 'javascript:;', $options);
                    },

                 ],


            ],
        ],
    ]); ?>

</div>
</div>

