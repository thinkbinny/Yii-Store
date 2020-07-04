<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员信息';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
 
    $('.layui-table tbody .select').click(function(){
        var _this = $(this);

        _this.addClass('active');
         _this.siblings().removeClass('active');
       
    })

");

$this->registerJs("
var index = parent.layer.getFrameIndex(window.name);
function submitSelect(name){ 

    var li   = $('.layui-table tbody .active');
    var attr = new Array()
    if(li.length === 0){
        layer.msg('请选择数据', {
          icon: 2,
          time: 1500 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
    }   
    
    var attr = new Array()
      li.each(function(){        
         attr.push({
         id:$(this).attr('data-id'),
         name:$(this).attr('data-name'),
         pic_url:$(this).attr('data-pic_url')
         })        
      }); 
      
    //console.log(attr)
  
    var iframe = window.parent.$('.layui-layer-iframe.win-popup')[0];
    if(iframe === undefined){ 
        var parentWindow = window.parent;
        eval(\"parentWindow.selectFile\"+name+\"(attr)\")
    }else{
        var frameId      = iframe.getElementsByTagName(\"iframe\")[0].id;console.log(frameId)
        var parentWindow = parent.document.getElementById(frameId).contentWindow;  
        eval(\"parentWindow.selectFile\"+name+\"(attr)\");       
    }
      //console.log(frameId)
     
            
    parent.layer.close(index);
}
",\yii\web\View::POS_END);

$this->registerCss("
    body{background:#fff;}
    .layui-table tbody tr{cursor: pointer;}
    .layui-table tbody .layui-icon-ok{display: none;}
    .layui-table tbody .active .layui-icon-ok{display: block;}
    .layui-form-radio{margin:0;padding-right:0;}
    .layui-form-radio > i{margin-right:0;}
");
?>


<div class="layuimini-container" style="border:none;">

    <div class="page-toolbar clearfix">
        <div class="layui-btn-group">

        </div>
        <!--搜索-->
        <div class="page-filter pull-right layui-search-form">
            <?
            $form = ActiveForm::begin([
                'fieldClass' => 'backend\widgets\ActiveSearch',
                'action' => ['select'],
                'method' => 'get',
                'options'=>['class'=>'layui-form'],
            ]);
            $searchtype = [
                'uid'   => '用户UID',
                'nickname'  => '用户昵称',
                'username'  => '用户名',
                'email'     => '电子邮箱',
                'mobile'    => '手机号码',
            ];
            $placeholder = '请输入' . $searchtype[$searchModel->searchtype];

            echo $form->field($searchModel, 'searchtype')
                ->dropDownList($searchtype,['lay-filter'=>'searchtype'])->label(false);
            echo $form->field($searchModel, 'title',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
                ->textInput(['class'=>'layui-input','placeholder'=>$placeholder,'id'=>'TitleSearch'])->label(false);
            echo  Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'layui-btn']);
            ActiveForm::end();
            ?>
        </div>
        <!--END 搜索-->
    </div>



    <?
echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'rowOptions'=>function($model){
            return [
              'class'=>'select',
              'data-name'=>$model->nickname,
              'data-pic_url'=>$model->headimgurl,
              'data-id'=>$model->uid,
            ];
        },
        'layout' => '{items} {pager}',//{summary}
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
                'label'=>'选择',
                'attribute' => 'uid',
                'format'    => 'raw',
                'contentOptions'=>['class'=>'text-center'],
                'options'=>['width'=>60,],
                'value'=>function($model){
                    return Html::tag('i','',['class'=>'layui-icon layui-icon-ok']);
                }

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
                'attribute'   =>  'score',
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


        ],
    ]); ?>

</div>


