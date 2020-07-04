<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);

?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'name')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入门店名称']);

        echo $form->field($model, 'logo_image_id')
            ->widget('extensions\uploads\Uploads',['msg'=>'建议上传100x100像素或等比例尺寸的图片']);
        echo $form->field($model, 'uid')
            ->widget('backend\components\select\Select',['readonly'=>true]);
        echo $form->field($model, 'linkman')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入门店联系人']);
        echo $form->field($model, 'phone')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入门店联系电话']);
        echo $form->field($model, 'shop_hours')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入门店营业时间'])
            ->hint('例如：8:30-17:30');
        //关联地区
        echo $form->field($model, 'district_id')
            ->widget('backend\components\relation\Region',[
                'table' =>  \backend\models\Region::className(),
                'url'   =>  \yii\helpers\Url::toRoute(['public/region']),
                'province'=>[
                    'attribute'=>'province_id',
                    'options'=>['class'=>'','prompt'=>'选择省份']
                ],
                'city'=>[
                    'attribute'=>'city_id',
                    'options'=>['class'=>'','prompt'=>'选择城市']
                ],
                'district'=>[
                    'attribute'=>'district_id',
                    'options'=>['class'=>'','prompt'=>'选择县/区']
                ]
            ])
        ->label('门店区域');


        echo $form->field($model, 'detail')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入详细地址']);


        echo $form->field($model, 'coordinate')
            ->widget('backend\components\map\Map');


        echo $form->field($model, 'summary')
            ->textarea(['maxlength' => true,'style'=>'width:700px;','placeholder'=>'请输入门店简介']);


         if(!$model->is_check){
            $model->is_check =key($model->getIsCheck());
        }

        if(empty($model->sort)){
            $model->sort = 50;
        }
        echo $form->field($model, 'sort')
            ->textInput(['maxlength' => true,'style'=>'width:150px;','placeholder'=>'请输入分组顺序']);
        echo $form->field($model, 'is_check')->radioList($model->getIsCheck(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

        echo $form->field($model, 'tixian_type')->radioList($model->getTixianType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
        echo $form->field($model, 'open_account')
            ->textInput(['maxlength' => true,'style'=>'width:300px;'])
            ->hint('例如：支付宝、中国农业银行');
        echo $form->field($model, 'realname')
            ->textInput(['maxlength' => true,'style'=>'width:300px;'])
            ->hint('');
        echo $form->field($model, 'account')
            ->textInput(['maxlength' => true,'style'=>'width:300px;'])
            ->hint('支付宝账号、银行卡号');

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
