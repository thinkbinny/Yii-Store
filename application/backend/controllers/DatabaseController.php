<?php
namespace backend\controllers;

use Yii;
use common\components\Database;
class DatabaseController extends BaseController {
    public $path;
    public  function init(){
        if(Yii::$app->requestedRoute!='database/restore'){
            parent::init();
        }
    }
    public function __construct($id, $module, array $config = [])
    {
        $this->path = YII_DIR.'/caches/data';
        parent::__construct($id, $module, $config);
    }

    public function actionExport() {
        $infos = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();//print_r($infos);exit;
        $infos = array_map('array_change_key_case', $infos);//print_r($infos);exit;
        //var_dump($infos);exit();
        return $this->render('export', [
            'infos' => $infos,
        ]);
    }

    public function actionBackups(){
        if(Yii::$app->request->isPost){
            $NOW_TIME = time();
            $tables = Yii::$app->request->post('tables');
            $path = $this->path;
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            if(empty($tables)){
                $this->error(Yii::t('backend','Please select a backup data table'));
            }
            //读取备份配置
            $config = array(
                'path'     => realpath($path) . DIRECTORY_SEPARATOR,
                'part'     => 10485760, //数据库备份卷大小 2097152 5242880 10485760
                'compress' => 0, //数据库备份文件是否启用压缩  0:不压缩 1:启用压缩
                'level'    => 9, //数据库备份文件压缩级别 1:普通 4:一般 9:最高
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error(Yii::t('backend','A backup task has been detected. Please try again later'));
            } else {
                //创建锁文件
                file_put_contents($lock, $NOW_TIME);
            }
            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error(Yii::t('backend','The backup directory does not exist or cannot be written. Please check and try again!'));
            Yii::$app->cache->set('backup_config',$config,86000);
            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', $NOW_TIME),
                'part' => 1,
            );
            Yii::$app->cache->set('backup_file',$file,86000);
            //缓存要备份的表
            Yii::$app->cache->set('backup_tables',$tables,86000);
            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                $this->success(Yii::t('backend','Initialize Success'),'',['tables' => $tables, 'tab' => $tab]);
            } else {
                $this->error(Yii::t('backend','Initialization failed and backup file creation failed!'));
            }


        }elseif(Yii::$app->request->isGet){
            $id     =   (int) Yii::$app->request->get('id');
            $start  =   (int) Yii::$app->request->get('start');
            $tables =   Yii::$app->cache->get('backup_tables');;
            //备份指定表
            $Database = new Database(Yii::$app->cache->get('backup_file'), Yii::$app->cache->get('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error(Yii::t('backend','Parameter Error'));//参数错误
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    $this->success(Yii::t('backend','Backup Complete'),'',['tab' => $tab]);
                } else { //备份完成，清空缓存
                    $path = Yii::$app->cache->get('backup_config');
                    unlink($path['path'] . 'backup.lock');
                    Yii::$app->cache->get('backup_tables', null);
                    Yii::$app->cache->get('backup_file', null);
                    Yii::$app->cache->get('backup_config', null);
                    $this->success(Yii::t('backend','Backup Complete'),'',['tab' => 0]); //备份完成
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                $this->success(Yii::t('backend','Backing Up...')."({$rate}%)",'',['tab' => $tab]);
            }
        } else { //出错
            $this->error(Yii::t('backend','Parameter Error'));//参数错误
        }

    }

    /**
     * @return string
     */
    public function actionImport() {
        //列出备份文件列表
        $path = $this->path;
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $glob = new \FilesystemIterator($path,  \FilesystemIterator::KEY_AS_FILENAME);
        $list = array();
        foreach ($glob as $name => $file) { $zipname = $name;
            if(preg_match('/^\d{8,8}-\d{6,6}?$/', $name)){
            $path2   = realpath($path.'/'.$name);
            $glob2  = new \FilesystemIterator($path2,  \FilesystemIterator::KEY_AS_FILENAME); //.'*.sql'
            $name   = sscanf($name, '%4s%2s%2s-%2s%2s%2s');
            $date   = "{$name[0]}-{$name[1]}-{$name[2]}";
            $time   = "{$name[3]}:{$name[4]}:{$name[5]}";
            foreach($glob2 as $filename => $file2){ $name = sscanf($filename, '%d');
                if(pathinfo($filename, PATHINFO_EXTENSION)=='sql'){
                    $part = $name[0];
                    if(isset($list["{$date} {$time}"])){
                        $info = $list["{$date} {$time}"];
                        $info['part'] = max($info['part'], $part);
                        $info['size'] = $info['size'] + $file2->getSize();
                    } else {
                        $info['part'] = $part;
                        $info['size'] = $file2->getSize();
                    }
                    if(file_exists(realpath($path.'/'.$zipname.'/'.$zipname.'.sql.zip'))){
                        $info['zipname']= true;
                    }else{
                        $info['zipname']=false;
                    }
                   //$path   = realpath($this->path.'/'.$dirname.'/'.$dirname.'.zip');
                   // $extension        = strtoupper(pathinfo($file2->getFilename(), PATHINFO_EXTENSION));
                   // $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                    $info['time']     = strtotime("{$date} {$time}");
                    $list["{$date} {$time}"] = $info;
                }
            }
            }
        }
       // print_r($list);exit;
        return $this->render('import', [
            'infos'=>$list,
        ]);
    }

    public function actionRepairOpt() {
        $operation  = Yii::$app->request->get('operation', '');
        $tables     = Yii::$app->request->get('tables', '');
        if($tables && in_array($operation, ['repair', 'optimize'])) {
            Yii::$app->db->createCommand($operation.' TABLE '.$tables);
            if($operation == 'optimize'){
                $this->success('优化成功');
            }else{
                $this->success('修复成功');
            }
            //$this->success(Yii::t('backend','Successful Operation'));

        }
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     * @author 七秒记忆 274397981@qq.com
     */
    public function actionDelete(){
        $time = (int) Yii::$app->request->get('time');
        if($time){
            $name  = '*.sql*';
            $path  = realpath($this->path.'/'.date('Ymd-His', $time)) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->error(Yii::t('backend','Backup file deletion failed. Check permissions!'));
            } else {
                @rmdir(realpath($this->path.'/'.date('Ymd-His', $time)));
                $this->success(Yii::t('backend','Backup file deleted Success'));
            }
        } else {
            $this->error(Yii::t('backend','Parameter Error'));//参数错误
        }
    }

    /**
     * 还原数据库
     * @author 七秒记忆 274397981@qq.com
     */
    public function actionRestore(){
        if(\Yii::$app->user->isGuest){
            $this->error(Yii::t('yii', 'You are not allowed to perform this action.'));
        }elseif(Yii::$app->user->identity->id != '1') {
            $this->error(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $time   = (int)Yii::$app->request->get('time');
        $part   = Yii::$app->request->get('part',null);
        $start  = Yii::$app->request->get('start',null);
        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = '*.sql';
            $path  = realpath($this->path.'/'.date('Ymd-His', $time)) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql$/', $basename);
                $list[$match[0]] = array($match[0], $name, $gz);
            }
            ksort($list);//print_r($list);exit;
            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                Yii::$app->cache->set('backup_list',$list,86400);//缓存备份列表
                $this->success(Yii::t('backend','Initialize Success'),'',['part' => 1,'start'=>0]);
            } else {
                $this->error(Yii::t('backend', 'The backup file may be damaged. Please check it!'));
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = Yii::$app->cache->get('backup_list');
            $db = new Database($list[$part], array(
                'path'     => realpath($this->path) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));

            $start = $db->import($start);

            if(false === $start){
                $this->error(Yii::t('backend', 'Error restoring data!'));
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $this->success(Yii::t('backend','Restoring...')."#{$part}",'',['part' => $part,'start'=>0]);
                } else {
                    Yii::$app->cache->delete('backup_list');
                    $this->success(Yii::t('backend', 'Restore Complete'));//还原完成
                }
            } else {

                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->success(Yii::t('backend','Restoring...')."#{$part} ({$rate}%)",'',['part' => $part,'start'=>$start[0]]);
                } else {
                    $this->success(Yii::t('backend','Restoring...')."#{$part}",'',['part' => $part,'start'=>$start[0],'gz'=>1]);
                }
            }

        } else {
            $this->error(Yii::t('backend','Parameter Error'));//参数错误
        }
    }

    /**
     * 打包文件ZIP
     * @author 七秒记忆 274397981@qq.com
     */
    public function actionZip(){
        $time   = (int)Yii::$app->request->get('time');
        $part   = Yii::$app->request->get('part',null);
        if(is_numeric($time) && is_null($part)){ //初始化
            $name   = '*.sql';
            $dirname = date('Ymd-His', $time);
            $path   = realpath($this->path.'/'.$dirname);
            $filepath   =  $path. DIRECTORY_SEPARATOR . $name;
            $files  = glob($filepath);

            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%d');
                $gz       = preg_match('/^\d+\.sql$/', $basename);
                $list[$match[0]] = array($match[0], $name, $gz);
            }
            ksort($list);//print_r($list);exit;
            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                Yii::$app->cache->set('zip_list',$list,86400);//缓存备份列表
                $this->success(Yii::t('backend','Initialization is complete and is being packaged...')."#1！",'',['part' => 1]);//初始化完成，正在打包...
            } else {
                $this->error(Yii::t('backend','The backup file may be damaged. Please check it!'));
            }
        }elseif(is_numeric($part)){
            $list  = Yii::$app->cache->get('zip_list');
            $last = end($list);
            $dirname = date('Ymd-His', $time);
            $path   = realpath($this->path.'/'.$dirname);
            $filename = $path.'/'.$dirname.'.sql.zip';
            $zip = new \ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
            if ($zip->open($filename, \ZIPARCHIVE::CREATE)!==TRUE) {
                $this->error(Yii::t('backend','Unable to open file, or file creation failed!'));//无法打开文件，或者文件创建失败
            }
            $file = $list[$part];
            if(file_exists($file['1'])){
                $zip->addFile( $file['1'], basename($file['1']));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
            $zip->close();
            if($last[0]==$part){ //打包完成
                $this->success(Yii::t('backend','Package Complete'));
            }else{
                $part++;
                $this->success(Yii::t('backend','Packing...')."#{$part}",'',['part' => $part]);//正在打包...  Packing...
            }
        }else {
            $this->error(Yii::t('backend','Parameter Error'));//参数错误
        }
    } //Yii::t('backend','Successful Operation')
    /**
     * 下载数据库文件
     * @author 七秒记忆 274397981@qq.com
     */
    public function actionDownload(){
        $time   = (int)Yii::$app->request->get('time');
        $dirname = date('Ymd-His', $time);
        $filename = realpath($this->path.'/'.$dirname.'/'.$dirname.'.sql.zip');
        if(!file_exists($filename)){
            $this->error(Yii::t('backend','The file does not exist It cannot be downloaded')); //文件不存在无法下载
        }
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename='.basename($filename)); //文件名
        header("Content-Type: application/".pathinfo($filename, PATHINFO_EXTENSION)); //zip格式的
        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
        header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小
        @readfile($filename);
    }

}