<?
use \common\components\Func;
if(!empty($field)):
    foreach ($field as $val):
        $setting    = unserialize($val['setting']);
        switch ($val['formtype'])
        {
            case 'catid':       //栏目
                $field = $val['field'];
                if ($model->isNewRecord) $model->$field = Yii::$app->request->get('category_id');

                echo $form->field($model,$val['field'],['options' => ['style' => 'display: none;']])
                    ->hiddenInput()
                    ->label(false);
                break;
            case 'title':       //标题
                //print_r($setting);
                echo $form->field($model, $val['field'])
                    ->textInput(['maxlength' => true,'placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->width(500);
                break;
            case 'keyword':     //来源
                echo $form->field($model, $val['field'])
                    ->textInput(['maxlength' => true,'placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->width(500);
                break;
            case 'copyfrom':    //标题
                echo $form->field($model, $val['field'])
                    ->textInput(['maxlength' => true,'placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->width(500);
                break;
            case 'islink':      //转向链接

                break;
            case 'text':        //单行文本
                echo $form->field($model, $val['field'])
                    ->textInput(['maxlength' => true,'placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->hint($val['tips'])
                    ->width(500);
                break;
            case 'textarea':    //多行文本
                $height = !empty($setting['enablehtml'])?$setting['enablehtml']: 100;
                if($setting['enablehtml']==1) //HTML模式
                {
                    echo $form->field($model, $val['field'])
                        ->widget('common\plugin\kindeditor\KindEditor',['editorType'=>'content-qq'])
                        ->label($val['name'])
                        ->hint($val['tips']);
                    break;
                }else{
                echo $form->field($model, $val['field'])
                    ->textarea(['maxlength' => true,'style'=>'padding: 10px;height: '.$height.'px;','placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->hint($val['tips'])
                    ->width(500);
                }
                break;
            case 'editor':      //编辑器
                echo $form->field($model, $val['field'])
                    ->widget('extensions\umeditor\Umeditor',[])
                    ->label($val['name'])
                    ->hint($val['tips'])
                    ->width(900);
                break;
            case 'box':         //选项
                $field = $val['field'];
                $options = Func::parse_config_attr($setting['options']);//print_r($options);exit;
                if ($model->isNewRecord) $model->$field = $setting['defaultvalue'];
                switch ($setting['boxtype']) {
                    case 'radio':       //单选按钮
                        echo $form->field($model, $val['field'])
                            ->radioList($options,[
                                'item' => function($index, $label, $name, $checked, $value)
                                {
                                    $checked=$checked?"checked":"";
                                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                                    return $return;
                                }
                            ]);
                        break;

                    case 'checkbox':       //复选框
                        if($val['field']=='position'){
                            $position = array();
                            foreach ($options as $k=>$v){
                                if(Func::check_document_position($model->position,$k))
                                    $position[] =   $k;
                            }
                            $model->position = $position;
                            echo $form->field($model, $val['field'])
                                ->checkboxList($options,[
                                    'item' => function($index, $label, $name, $checked, $value)
                                    {
                                        $checked=$checked?"checked":"";
                                        $return = "<input name=\"{$name}\" lay-skin=\"primary\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"checkbox\">";
                                        return $return;
                                    }
                                ]);
                        }else{
                            echo $form->field($model, $val['field'])
                                ->checkboxList($options,[
                                    'item' => function($index, $label, $name, $checked, $value)
                                    {
                                        $checked=$checked?"checked":"";
                                        $return = "<input name=\"{$name}\" lay-skin=\"primary\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"checkbox\">";
                                        return $return;
                                    }
                                ]);
                        }
                        break;
                    case 'select':       //选择
                        echo $form->field($model, $val['field'])
                            ->dropDownList($options,['encode' => true,'lay-filter'=>'formtype','prompt' => '请选择']);
                        break;
                    default:
                }
                break;
            case 'image':         //图片 0文本 1图片模式
                echo $form->field($model, $val['field'])
                    ->widget('extensions\uploads\Uploads',['msg'=>'建议上传300x300像素或等比例尺寸的图片'])
                    ->label($val['name'])
                    ->hint($val['tips']);

                break;
            case 'number':         //数字
                echo $form->field($model, $val['field'])
                    ->textInput(['maxlength' => true,'placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->width(200);
                break;
            case 'datetime':         //日期和时间
                $type = 'date';
                $field = $val['field'];

                if(empty($model->$field)) $model->$field='';
                //if ($model->isNewRecord) $model->$val['field'] = date('Y-m-d');else $model->$val['field'] = date('Y-m-d',$model->$val['field']);
                if($setting['fieldtype']==0){
                    $type = 'datetime';
                    //if ($model->isNewRecord) $model->$val['field'] = date('Y-m-d H:i:s');else $model->$val['field'] = date('Y-m-d',$model->$val['field']);
                }
                echo $form->field($model, $val['field'])
                    ->textInput(['lay-verify'=>'date','id'=>'lay-'.$val['field'],'class'=>'layui-input','autocomplete'=>'off','maxlength' => true,'placeholder'=>'请输入'.$val['name']])
                    ->label($val['name'])
                    ->width(200);

                $this->registerJs('
                            layui.use([\'layer\', \'form\'], function(){
                                var laydate = layui.laydate;   
                                laydate.render({
                                    elem: "#lay-'.$val['field'].'",
                                    type: \''.$type.'\'
                                });
                            });
                        ');
                break;
            case 'linkage':         //联动菜单

                break;
            case 'map':         //地图字段

                break;
            case 'omnipotent':         //万能字段

                break;
            default:

        }

    endforeach;
endif;
?>