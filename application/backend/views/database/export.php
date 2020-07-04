<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\Func;
$this->params['thisUrl'] = 'database/export';
$this->title = '数据库管理';
$this->params['breadcrumbs'][] = ['label' => '数据库设置', 'url' => ['basic']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    //监听表格复选框选择
    //全选
        form.on('checkbox(checkall)', function (data) {
            var child = $(data.elem).parents('table').find('tbody input[type=\"checkbox\"]');  
            child.each(function(index, item){  
              item.checked = data.elem.checked;  
            });  
            form.render('checkbox'); 
        });

});
   

   
");
$this->registerJs("
        var exports = $('.export'),form = $('#export-form'),tables;
        exports.click(function(){
            if($(this).hasClass('disabled')==true){
                popup.alert('检测到有一个备份任务正在执行，请稍后再试！');  return false;
            }
            exports.parent().children().addClass('disabled');
                    exports.html('正在发送备份请求...');
                    $.post(
                        form.attr('action'),
                        form.serialize(),
                        function(data){
                            if(data.status){
                                tables = data.tables;
                                exports.html(data.message + '开始备份，请不要关闭本页面！');
                                backup(data.tab);
                                window.onbeforeunload = function(){ return '正在备份数据库，请不要关闭！' }
                            } else {
                                popup.alert(data.message);
                                exports.parent().children().removeClass('disabled');
                                exports.html('立即备份');
                                setTimeout(function(){
                                    $('#top-alert').find('button').click();
                                    $(that).removeClass('disabled').prop('disabled',false);
                                },1500);
                            }
                        },
                        'json'
                    );
              return false;
        })



         function backup(tab, status){
            status && showmsg(tab.id, '开始备份...(0%)');
            $.get(form.attr('action'), tab, function(data){
                if(data.status){
                    showmsg(tab.id, data.message);
                    if(!$.isPlainObject(data.tab)){
                        exports.parent().children().removeClass('disabled');
                        exports.html('备份完成，点击重新备份');
                        window.onbeforeunload = function(){ return null }
                        return;
                    }
                    backup(data.tab, tab.id != data.tab.id);
                } else {
                    popup.alert(data.message);
                    exports.parent().children().removeClass('disabled');
                    exports.html('立即备份');
                    setTimeout(function(){
    	                $('#top-alert').find('button').click();
    	                $(that).removeClass('disabled').prop('disabled',false);
    	            },1500);
                }
            }, 'json');

        }
        function showmsg(id, msg){
            form.find('input[value=' + tables[id] + ']').closest('tr').find('.msginfo').html(msg);
        }
");

?>
<div class="layuimini-container">
    <div class="layuimini-main">
<?=$this->render('_tab_menu');?>


    <?php ActiveForm::begin([
        'method'=>'post',
        'id'=>'export-form',
        'action'=>\yii\helpers\Url::to(['backups']),
        'options' => ['class'=>'',]
    ]); ?>
        <div class="page-toolbar">
            <div class="layui-btn-group">

                <?php
                // layui-icon layui-icon-add-circle-fine
                echo Html::button('立即备份',['class' => 'layui-btn layui-btn-primary export']);


                ?>


            </div>
            <div class="page-filter pull-right">


            </div>
        </div>
    <div class="layui-form">
    <table class="layui-table"><!-- table table-striped table-advance table-hover -->
        <thead>
        <thead>
        <tr>
            <th class="tablehead" style="width: 40px;">
                <?
                echo Html::checkbox('all',false,['class'=>'check-all','lay-skin'=>'primary','lay-filter'=>'checkall']);
                ?>
            </th>
            <th class="tablehead">表名</th>
            <th class="tablehead" style="width: 90px;">类型</th>
            <th class="tablehead" style="width: 160px;">编码</th>
            <th class="tablehead" style="width: 90px;">记录数</th>
            <th class="tablehead" style="width: 110px;">使用空间</th>
            <th class="tablehead" style="width: 110px;">碎片</th>
            <th class="tablehead" style="width: 170px;">创建时间</th>
            <th class="tablehead" style="width: 150px;">备份状态</th>
            <th class="tablehead" style="width: 110px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($infos as $info) { ?>
            <tr>
                <td>
                    <?=Html::checkbox('tables[]',false,['lay-skin'=>'primary','value'=>$info['name']])?>
                    </td>
                <td><?php echo $info['name'];?></td>
                <td><?php echo $info['engine'];?></td>
                <td><?php echo $info['collation'];?></td>
                <td><?php echo $info['rows'];?></td>
                <td><?php echo Func::sizeCount($info['data_length'] + $info['index_length']);?></td>
                <td><?php echo Func::sizeCount($info['data_free']);?></td>
                <td><?php echo $info['create_time'];?></td>
                <td class="msginfo">未备份</td>
                <td>
                    <?=Html::a('优化', ['repair-opt', 'operation' => 'optimize', 'tables' => $info['name']], [
                        'title' => '优化',
                        'class' => 'btn btn-primary btn-xs ajax-get'
                    ]);?>
                    <?=Html::a('修复', ['repair-opt', 'operation' => 'repair', 'tables' => $info['name']], [
                        'title' => '优化',
                        'class' => 'btn btn-info btn-xs ajax-get'
                    ]);?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>

        <div class="page-toolbar" style="padding-top: 10px;">
            <div class="layui-btn-group">

                <?php
                // layui-icon layui-icon-add-circle-fine
                echo Html::button('立即备份',['class' => 'layui-btn layui-btn-primary export']);


                ?>


            </div>
            <div class="page-filter pull-right">


            </div>
        </div>


    </div>
    <?php ActiveForm::end(); ?>


    </div>
</div>
