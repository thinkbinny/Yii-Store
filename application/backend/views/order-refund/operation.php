<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\DetailView;
use common\components\Func;
/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = $model->getModelTypeText();
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
//verifyType
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    form.on('radio(verifyType)', function (data) {
       
        if(data.value == 1){
            $('.address_id').show();       
        }else{
            $('.address_id').hide();              
        }
    });
});


function mySubmit(){ 
    
     parent.layer.confirm("确认要执行该操作吗？", {icon: 3, title:'提示'}, function(index){
         $('.ajax-submit').click();
        parent.layer.close(index);
        return false;
    });
    return false;
    
  
}
JS;
$this->registerJs($js,\yii\web\View::POS_END);
$js = <<<JS
 $('.operation').selectWindows({
    title:'退款到用户余额'
 });
JS;
$this->registerJs($js);

$css = <<<CSS
.address_id{display:inline-block;}
.layui-table .goods-detail > div > div{float: left;}
.layui-table .goods-detail .goods {margin-bottom: 5px;}
.layui-table .goods-detail .goods-image{margin-right: 6px;}
.layui-table .goods-detail .goods-image .pic_url {width: 72px;height: 72px;background: no-repeat center center / 100%; }
.layui-table .goods-detail .goods-info{width: 340px;height: 72px;}
.layui-table .goods-detail .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.layui-table .goods-detail .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.layui-table .goods-detail .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }

.operation label{width: 80px;font-weight:400;text-align: right;}
.operation p{margin: 3px 0;}
CSS;
$this->registerCss($css);

?>
<div class="layuimini-container">
    <div class="layuimini-main">
        <?
        $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form'],

        ]);

        echo DetailView::widget([
            'model' => $model,
            'options'=>['class'=>'layui-table'],
            'attributes' => [
                [
                    'attribute'     =>  'id',
                    'label'         =>  '退款ID',
                    'captionOptions'=>['style'=>'width:150px;']
                ],
                [
                    'captionOptions'=>['style'=>'width:150px;'],
                    'attribute' =>  'order_sn',
                    'label'     =>  '订单号'
                ],
               [
                    'attribute' =>  'uid',
                    'label'     =>  '退回用户',
                    'value'     =>  function ($model){
                        return Func::get_nickname($model->uid).'【id：'.$model->uid.'】';
                    }
                ],

                [
                    'attribute' =>  'refund_deposit',
                    'value'     =>  function ($model){
                        return '￥'.$model->refund_money;
                    }
                ],
                [
                    'attribute' =>  'status',
                    //'label'     =>  '审核意见',
                    'format'    =>  'raw',
                    'value'     =>  function ($model){
                        if( $model->status == 0 ){
                            $dropDownList =  '';
                            if($model->type == 2 || $model->type == 3){
                                $data = $model->getReturnAddress();
                                $addressIdName = Html::getInputName($model,'address_id');
                                $dropDownList = Html::dropDownList($addressIdName,false,$data,['prompt'=>'请选择收货地址']);
                            }
                            $dropDownList = Html::tag('div',$dropDownList,['style'=>'width: 425px;display: none;','class'=>'address_id','id'=>Html::getInputId($model,'address_id')]//display:inline-block
                            );

                            $inputName = Html::getInputName($model,'verify');
                            $return = Html::radioList($inputName,null,['0'=>'拒绝通过','1'=>'审核通过'],['style'=>'display: inline-block',
                                'item' => function($index, $label, $name, $checked, $value)
                                {
                                    $checked=$checked?"checked":"";
                                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"verifyType\">";
                                    return $return;
                                }
                            ]);
                            return Html::tag('div',$return . $dropDownList,['id'=>Html::getInputId($model,'verify')]);
                        }else{
                            return $model->getStatusText();
                        }
                    }
                ],
                [
                    'attribute' =>  'created_at',
                    'label'     =>  '申请日期',
                    'format'    =>  ['date','Y-MM-dd H:i:s'],
                ],

            ],
        ]);
        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
        ActiveForm::end();
        ?>
    </div>
</div>
