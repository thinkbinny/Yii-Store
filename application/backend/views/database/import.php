<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\Func;
$this->params['thisUrl'] = 'database/import';
$this->title = '数据备份';
$this->params['breadcrumbs'][] = ['label' => '数据库设置', 'url' => ['basic']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("

    $('.db-import').click(function(){

       
      var self = this, status = '.';
      parent.layer.confirm('您确定还原数据吗？', {icon: 3, title:'提示'}, function(index){
            parent.layer.close(index);
            $.get(self.href, success, 'json');
            window.onbeforeunload = function(){ return '正在还原数据库，请不要关闭！' }
            return false;
            function success(data){
                if(data.status){
                    if(data.gz){
                        data.message += status;
                        if(status.length === 5){
                            status = '.';
                        } else {
                            status += '.';
                        }
                    }
                    $(self).parent().prev().text(data.message);
                    if(data.part){
                        $.get(self.href,
                            {'part' : data.part, 'start' : data.start},
                            success,
                            'json'
                        );
                    }  else {
                        window.onbeforeunload = function(){ return null; }
                    }
                } else {
                     popup.alert(data.message);
                }
            }       
       return false;
      });return false;
    });

        //////////////
    $('.db-zip').click(function(){
    
    var self = this, status = '.';
        parent.layer.confirm('您确定打包该备份吗？', {icon: 3, title:'提示'}, function(index){
            parent.layer.close(index);
            $.get(self.href, successzip, 'json');
            window.onbeforeunload = function(){ return '正在打包文件，请不要关闭！' }
            return false;
            function successzip(data){ 
                if(data.status){
                    if(data.gz){
                        data.message += status;
                        if(status.length === 5){
                            status = '.';
                        } else {
                            status += '.';
                        }
                    }
                    $(self).parent().prev().text(data.message);
                    if(data.part){
                        $.get(self.href,
                            {'part' : data.part},
                            successzip,
                            'json'
                        );
                    }  else {                                                                
                        window.onbeforeunload = function(){    return null; }
                        //window.location.reload(); //打包完成
                    }
                } else {
                     window.onbeforeunload = function(){ return null; }
                     popup.alert(data.message);
                }
            }
         });
         return false;    
    });
");


?>
<div class="layuimini-container">
    <div class="layuimini-main">
<?=$this->render('_tab_menu');?>

    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <thead>
                    <tr>
                        <th class="tablehead" style="width: 150px;">备份名称</th>
                        <th class="tablehead" style="width: 70px;">卷数</th>

                        <th class="tablehead" >数据大小</th>
                        <th class="tablehead" style="width: 170px;">备份时间</th>
                        <th class="tablehead" style="width: 220px;">状态</th>
                        <th class="tablehead" style="width: 200px;">操作</th>
                    </tr>
                </thead>
            <tbody>
            <?php foreach($infos as $key=> $info) { ?>
                <tr>
                    <td><?php echo date('Ymd-His',$info['time']);?></td>
                    <td><?php echo $info['part'];?></td>

                    <td><?php echo Func::sizeCount($info['size']);?></td>
                    <td><?php echo $key;?></td>
                    <td >--</td>
                    <td>
                        <?=Html::a('还原', ['restore', 'time' => $info['time']], [
                            'title' => '还原',
                            'class' => 'btn btn-primary btn-xs db-import'
                        ]);?>
                        <?=Html::a('删除', ['delete', 'time' => $info['time']], [
                            'title' => '删除',
                            'class' => 'btn btn-info btn-xs ajax-get confirm'
                        ]);?>
                        <?=Html::a('打包', ['zip', 'time' => $info['time']], [
                            'title' => '打包',
                            //'target'=> '_blank',
                            'class' => 'btn btn-danger btn-xs db-zip'
                        ]);?>
                        <?
                        if(!empty($info['zipname'])){
                            echo Html::a('下载', ['download', 'time' => $info['time']], [
                                'title' => '下载',
                                'target'=> '_blank',
                                'class' => 'btn btn-success btn-xs'
                            ]);
                        }
                        ?>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
</div>
