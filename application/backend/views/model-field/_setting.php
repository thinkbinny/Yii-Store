<?php
$this->registerCss("
.iframe-popup .form-formt .layui-form-label{width:120px;}
");
$setting        = array();
$settingHtml    = array();
if(!empty($model->setting)){
    $settingHtml = unserialize($model->setting);
}

$size           = isset($settingHtml['size'])?$settingHtml['size']:50;
$defaultvalue   = isset($settingHtml['defaultvalue'])?$settingHtml['defaultvalue']:'';
$ispassword     = isset($settingHtml['ispassword'])?$settingHtml['ispassword']:1;
$ispassword1    = $ispassword==1?'checked=""':'';
$ispassword0    = $ispassword==0?'checked=""':'';
$setting['text'] =
    '<div class="layui-form-item">
        <label class="layui-form-label">文本框长度</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="setting[size]" value="'.$size.'">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认值</label>
        <div class="layui-input-inline" style="width: 300px;">
            <input type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否为密码框</label>
        <div class="layui-input-inline">
            <input name="setting[ispassword]" value="1" title="是" '.$ispassword1.' type="radio">
            <input name="setting[ispassword]" value="0" title="否" '.$ispassword0.' type="radio">   
        </div>
    </div>';
$width           = isset($settingHtml['width'])?$settingHtml['width']:100;
$height          = isset($settingHtml['height'])?$settingHtml['height']:45;
$defaultvalue   = isset($settingHtml['defaultvalue'])?$settingHtml['defaultvalue']:'';
$enablehtml     = isset($settingHtml['enablehtml'])?$settingHtml['enablehtml']:0;
$enablehtml1    = $enablehtml==1?'checked':'';
$enablehtml0    = $enablehtml==0?'checked':'';
$setting['catid'] = '
        <div class="layui-form-item">
            <label class="layui-form-label">宽度</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="setting[width]" value="'.$width.'">
            </div>
            <div class="layui-form-mid layui-word-aux"> px</div>
        </div>
';
$setting['title'] = '

        <div class="layui-form-item">
            <label class="layui-form-label">默认值</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">                
            </div>
        </div>
';
$setting['keyword'] = '

        <div class="layui-form-item">
            <label class="layui-form-label">默认值</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">                
            </div>
        </div>
';
$setting['copyfrom'] = '

        <div class="layui-form-item">
            <label class="layui-form-label">默认值</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">                
            </div>
        </div>
';
$setting['islink'] = '
        <div class="layui-form-item">
            <label class="layui-form-label">默认值</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">                
            </div>
        </div>
';

$setting['textarea'] = '
        <div class="layui-form-item">
            <label class="layui-form-label">文本域宽度</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="setting[width]" value="'.$width.'">
            </div>
            <div class="layui-form-mid layui-word-aux"> %</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文本域高度</label>
            <div class="layui-input-inline" >
                <input type="text" class="layui-input" name="setting[height]" value="'.$height.'">
            </div>
            <div class="layui-form-mid layui-word-aux"> px</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">默认值</label>
            <div class="layui-input-inline" style="width: 500px;">
                <textarea name="setting[defaultvalue]" style="padding: 10px;height: 100px;" class="layui-input">'.$defaultvalue.'</textarea>                
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否允许Html</label>
            <div class="layui-input-inline">
                <input name="setting[enablehtml]" value="1" title="是" '.$enablehtml1.' type="radio">
                <input name="setting[enablehtml]" value="0" title="否" '.$enablehtml0.' type="radio">   
            </div>
        </div>
';
$width           = isset($settingHtml['width'])?$settingHtml['width']:100;
$height          = isset($settingHtml['height'])?$settingHtml['height']:200;
$defaultvalue   = isset($settingHtml['defaultvalue'])?$settingHtml['defaultvalue']:'';
$toolbar     = isset($settingHtml['toolbar'])?$settingHtml['toolbar']:0;
$toolbar1    = $toolbar==1?'checked=""':'';
$toolbar0    = $toolbar==0?'checked=""':'';
$setting['editor']  = '
    <div class="layui-form-item">
        <label class="layui-form-label">编辑器宽度</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="setting[width]" value="'.$width.'">
        </div>
        <div class="layui-form-mid layui-word-aux"> %</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">编辑器高度</label>
        <div class="layui-input-inline" >
            <input type="text" class="layui-input" name="setting[height]" value="'.$height.'">
        </div>
        <div class="layui-form-mid layui-word-aux"> px</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认值</label>
        <div class="layui-input-inline" style="width: 500px;">
            <textarea name="setting[defaultvalue]"style="padding: 10px;height: 100px;" class="layui-input">'.$defaultvalue.'</textarea>
            
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">编辑器样式</label>
        <div class="layui-input-inline">
            <input name="setting[toolbar]" value="1" title="简洁型" '.$toolbar1.' type="radio">
            <input name="setting[toolbar]" value="0" title="标准型" '.$toolbar0.' type="radio">   
        </div>
    </div>
';
$options         = isset($settingHtml['options'])?$settingHtml['options']:'';
$boxtype         = isset($settingHtml['boxtype'])?$settingHtml['boxtype']:'radio';
$boxtype_radio        = $boxtype=='radio'?'checked=""':'';
$boxtype_checkbox     = $boxtype=='checkbox'?'checked=""':'';
$boxtype_select       = $boxtype=='select'?'checked=""':'';
$defaultvalue         = isset($settingHtml['defaultvalue'])?$settingHtml['defaultvalue']:'';
$fieldtype            = isset($settingHtml['fieldtype'])?$settingHtml['fieldtype']:'varchar';

$fieldtypeHtml        = '';
$fieldtypeHtml        = '<select name="setting[fieldtype]">';
if($fieldtype=='varchar'){
    $fieldtypeHtml        .= '<option value="varchar" selected>字符 VARCHAR</option>';
}else{
    $fieldtypeHtml        .= '<option value="varchar">字符 VARCHAR</option>';
}
if($fieldtype=='tinyint'){
    $fieldtypeHtml        .= '<option value="tinyint" selected>整数 TINYINT(3)</option>';
}else{
    $fieldtypeHtml        .= '<option value="tinyint">整数 TINYINT(3)</option>';
}
if($fieldtype=='smallint'){
    $fieldtypeHtml        .= '<option value="smallint" selected>整数 SMALLINT(5)</option>';
}else{
    $fieldtypeHtml        .= '<option value="smallint">整数 SMALLINT(5)</option>';
}
if($fieldtype=='mediumint'){
    $fieldtypeHtml        .= '<option value="mediumint" selected>整数 MEDIUMINT(8)</option>';
}else{
    $fieldtypeHtml        .= '<option value="mediumint">整数 MEDIUMINT(8)</option>';
}
if($fieldtype=='int'){
    $fieldtypeHtml        .= '<option value="int" selected>整数 INT(10)</option>';
}else{
    $fieldtypeHtml        .= '<option value="int">整数 INT(10)</option>';
}
$fieldtypeHtml        .= '</select> ';

$setting['box']  = '
            <div class="layui-form-item">
                <label class="layui-form-label">选项列表</label>
                <div class="layui-input-inline" style="width: 500px;">
                    <textarea name="setting[options]"style="padding: 10px;height: 100px;" placeholder="选项值1:选项名称1" class="layui-input">'.$options.'</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">选项类型</label>
                <div class="layui-input-inline" style="width:auto">
                    <input name="setting[boxtype]" value="radio" title="单选按钮" type="radio" '.$boxtype_radio.' >
                    <input name="setting[boxtype]" value="checkbox" title="复选框" type="radio" '.$boxtype_checkbox.'>   
                    <input name="setting[boxtype]" value="select" title="下拉框" type="radio" '.$boxtype_select.'>   
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">字段类型</label>
                <div class="layui-input-inline" >
                    '.$fieldtypeHtml.'                          
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">默认值</label>
                <div class="layui-input-inline" style="width: 500px;">
                    <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">                            
                </div>
            </div>
';
$size           = isset($settingHtml['size'])?$settingHtml['size']:500;
$defaultvalue   = isset($settingHtml['defaultvalue'])?$settingHtml['defaultvalue']:'';
$upload_allowext= isset($settingHtml['upload_allowext'])?$settingHtml['upload_allowext']:'gif|jpg|jpeg|png|bmp';
$show_type      = isset($settingHtml['show_type'])?$settingHtml['show_type']:0;
$show_type1    = $show_type==1?'checked=""':'';
$show_type0    = $show_type==0?'checked=""':'';
$watermark      = isset($settingHtml['watermark'])?$settingHtml['watermark']:0;
$watermark1    = $watermark==1?'checked=""':'';
$watermark0    = $watermark==0?'checked=""':'';
$isselectimage      = isset($settingHtml['isselectimage'])?$settingHtml['isselectimage']:0;
$isselectimage1     = $isselectimage==1?'checked=""':'';
$isselectimage0     = $isselectimage==0?'checked=""':'';
$images_width           = isset($settingHtml['images_width'])?$settingHtml['images_width']:150;
$images_height           = isset($settingHtml['images_height'])?$settingHtml['images_height']:150;

$setting['image']   = '
    <div class="layui-form-item">
        <label class="layui-form-label">文本框长度</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="setting[size]" size="10" value="'.$size.'">
        </div>   
        <div class="layui-form-mid layui-word-aux"> px</div>     
    </div>
     <div class="layui-form-item">
        <label class="layui-form-label">默认值</label>
        <div class="layui-input-inline" style="width: 500px;">
            <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" value="'.$defaultvalue.'">            
        </div>
        
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">表单显示模式</label>
        <div class="layui-input-inline" style="width: 500px;">
            <input name="setting[show_type]" value="1" title="图片模式" type="radio" '.$show_type1.'>
            <input name="setting[show_type]" value="0" title="文本框模式" type="radio" '.$show_type0.'>   
        </div>        
    </div>    
    <div class="layui-form-item" >
        <label class="layui-form-label">允许上传的图片类型</label>
        <div class="layui-input-inline" style="width: 500px;">
            <input type="text" class="layui-input" name="setting[upload_allowext]" size="40" value="'.$upload_allowext.'">
        </div>        
    </div>        
    <div class="layui-form-item">
        <label class="layui-form-label">是否在图片上添加水印</label>
        <div class="layui-input-inline">
            <input name="setting[watermark]" value="1" title="是" type="radio" '.$watermark1.'>
            <input name="setting[watermark]" value="0" title="否" type="radio" '.$watermark0.'>   
        </div>        
    </div>  
    <div class="layui-form-item">
        <label class="layui-form-label">是否从已上传中选择</label>
        <div class="layui-input-inline" >
            <input name="setting[isselectimage]" value="1" title="是" type="radio" '.$isselectimage1.'>
            <input name="setting[isselectimage]" value="0" title="否" type="radio" '.$isselectimage0.'>   
        </div>        
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图像大小</label>
        <div class="layui-input-inline" >
            宽 <input type="text" style="width: 50px;display:inline-block" class="layui-input" name="setting[images_width]" size="3" value="'.$images_width.'"> px
            高 <input type="text" style="width: 50px;display:inline-block" class="layui-input" name="setting[images_height]" size="3" value="'.$images_height.'"> px   
        </div>        
    </div>
';
$minnumber           = isset($settingHtml['minnumber'])?$settingHtml['minnumber']:1;
$maxnumber           = isset($settingHtml['maxnumber'])?$settingHtml['maxnumber']:'';
$defaultvalue        = isset($settingHtml['defaultvalue'])?$settingHtml['defaultvalue']:'';
$decimaldigits       = isset($settingHtml['decimaldigits'])?$settingHtml['decimaldigits']:'-1';
$decimaldigitsHtml   = '<select name="setting[decimaldigits]" >';
$decimaldigitsHtml .= $decimaldigits=='-1'?'<option selected value="-1">自动</option>':'<option value="-1">自动</option>';
$decimaldigitsHtml .= $decimaldigits=='0'?'<option selected value="0">0</option>':'<option value="0">0</option>';
$decimaldigitsHtml .= $decimaldigits=='1'?'<option selected value="1">1</option>':'<option value="1">1</option>';
$decimaldigitsHtml .= $decimaldigits=='2'?'<option selected value="2">2</option>':'<option value="2">2</option>';
$decimaldigitsHtml .= $decimaldigits=='3'?'<option selected value="3">3</option>':'<option value="3">3</option>';
$decimaldigitsHtml .= $decimaldigits=='4'?'<option selected value="4">4</option>':'<option value="4">4</option>';
$decimaldigitsHtml .= $decimaldigits=='5'?'<option selected value="5">5</option>':'<option value="5">5</option>';
$decimaldigitsHtml .='</select>';
    $setting['number'] = '
        <div class="layui-form-item">
            <label class="layui-form-label">取值范围</label>
            <div class="layui-input-inline" >
                <input type="text" style="width: 50px;display:inline-block" class="layui-input" name="setting[minnumber]" size="5" value="'.$minnumber.'"> -
                <input type="text" style="width: 50px;display:inline-block" class="layui-input" name="setting[maxnumber]" size="5" value="'.$maxnumber.'">   
            </div>        
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">小数位数</label>
            <div class="layui-input-inline" >
                '.$decimaldigitsHtml.'                            
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">默认值</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input placeholder="" type="text" class="layui-input" name="setting[defaultvalue]" size="40" value="'.$defaultvalue.'">            
            </div>        
        </div>
    ';
$fieldtype      = isset($settingHtml['fieldtype'])?$settingHtml['fieldtype']:0;
$fieldtype1     = $fieldtype==1?'checked=""':'';
$fieldtype0     = $fieldtype==0?'checked=""':'';
    $setting['datetime'] = '
        <div class="layui-form-item">
            <label class="layui-form-label">时间格式</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input name="setting[fieldtype]" value="1" title="日期（'.date('Y-m-d').'）" type="radio" '.$fieldtype1.'>
                <input name="setting[fieldtype]" value="0" title="日期+时间（'.date('Y-m-d H:i:s').'）" type="radio" '.$fieldtype0.' >   
            </div>        
        </div>
    ';
$formtext= isset($settingHtml['formtext'])?$settingHtml['formtext']:'';
$decimaldigits       = isset($settingHtml['fieldtype'])?$settingHtml['fieldtype']:'varchar';
$fieldtypeHtml   = '<select name="setting[decimaldigits]" >';
$fieldtypeHtml .= $decimaldigits=='varchar'?'<option selected value="varchar">字符 VARCHAR</option>':'<option value="varchar">字符 VARCHAR</option>';
$fieldtypeHtml .= $decimaldigits=='tinyint'?'<option selected value="tinyint">整数 TINYINT(3)</option>':'<option value="tinyint">整数 TINYINT(3)</option>';
$fieldtypeHtml .= $decimaldigits=='smallint'?'<option selected value="smallint">整数 SMALLINT(5)</option>':'<option value="smallint">整数 SMALLINT(5)</option>';
$fieldtypeHtml .= $decimaldigits=='mediumint'?'<option selected value="mediumint">整数 MEDIUMINT(8)</option>':'<option value="mediumint">整数 MEDIUMINT(8)</option>';
$fieldtypeHtml .= $decimaldigits=='int'?'<option selected value="int">整数 INT(10)</option>':'<option value="int">整数 INT(10)</option>';
$fieldtypeHtml .='</select>';
    $setting['omnipotent'] = '
        <div class="layui-form-item">
            <label class="layui-form-label">表单</label>
            <div class="layui-input-inline" style="width: 500px;"> 
                <textarea name="setting[formtext]" rows="2" cols="20" placeholder="例如：<input type=\'text\' name=\'info[voteid]\' id=\'voteid\' value=\'{FIELD_VALUE}\' style=\'50\' >" class="layui-input" style="height:100px;width:100%;">'.$formtext.'</textarea>
            </div>        
        </div>
        <div class="layui-form-item">
                <label class="layui-form-label">字段类型</label>
                <div class="layui-input-inline" >
                    '.$fieldtypeHtml.'                           
                </div>
            </div>
    ';
return $setting;
?>