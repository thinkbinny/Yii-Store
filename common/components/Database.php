<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace common\components;
use Yii;

//数据导出模型
class Database{
    /**
     * 文件指针
     * @var resource
     */
    private $fp;

    /**
     * 备份文件信息 part - 卷号，name - 文件名
     * @var array
     */
    private $file;

    /**
     * 当前打开文件大小
     * @var integer
     */
    private $size = 0;

    /**
     * 备份配置
     * @var integer
     */
    private $config;
    //private 表
    private $table;
    //开始
    private $start;
    /**
     * 数据库备份构造方法
     * @param array  $file   备份或还原的文件信息
     * @param array  $config 备份配置信息
     * @param string $type   执行类型，export - 备份数据， import - 还原数据
     */
    public function __construct($file, $config, $type = 'export'){
        $this->file   = $file;
        $this->config = $config;
    }

    /**
     * 打开一个卷，用于写入数据
     * @param  integer $size 写入数据的大小
     */
    private function open($size){
        if($this->fp){
            $this->size += $size;
            if($this->size > $this->config['part']){
                $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
                $this->fp = null;
                $this->file['part']++;
                Yii::$app->cache->set('backup_file',$this->file,86400);
                $this->create();
            }
        } else {
            $backuppath = $this->config['path'];
            $filename = "{$backuppath}{$this->file['name']}";
            if(!is_dir($filename)){
                mkdir($filename, 0755, true);
            }
            $filename   = $filename."/{$this->file['part']}.sql";
            if($this->config['compress']){
                $filename = "{$filename}.gz";
                $this->fp = @gzopen($filename, "a{$this->config['level']}");
            } else {
                $this->fp = @fopen($filename, 'a');
            }
            $this->size = filesize($filename) + $size;
            if($this->size > $this->config['part']){
                $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
                $this->fp = null;
                $this->file['part']++;
                Yii::$app->cache->set('backup_file',$this->file,86400);
                if($this->start>0){
                    $this->create();
                }
            }
        }
    }

    /**
     * 写入初始数据
     * @return boolean true - 写入成功，false - 写入失败
     */
    public function create(){
        $sql  = "-- -----------------------------\n";
        $sql .= "-- Think MySQL Data Transfer \n";
        $sql .= "-- \n";
        $sql .= "-- Host     : HOST\n";
        $sql .= "-- Port     : PORT\n";
        $sql .= "-- Database : NAME\n";
        $sql .= "-- \n";
        $sql .= "-- Part : #{$this->file['part']}\n";
        $sql .= "-- Date : " . date("Y-m-d H:i:s") . "\n";
        $sql .= "-- -----------------------------\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        return $this->write($sql);
    }

    /**
     * 写入SQL语句
     * @param  string $sql 要写入的SQL语句
     * @return boolean     true - 写入成功，false - 写入失败！
     */
    private function write($sql){
        $size = strlen($sql);
        //由于压缩原因，无法计算出压缩后的长度，这里假设压缩率为50%，
        //一般情况压缩率都会高于50%；
        $size = $this->config['compress'] ? $size / 2 : $size;
        $this->open($size); 
        return $this->config['compress'] ? @gzwrite($this->fp, $sql) : @fwrite($this->fp, $sql);
    }
    public function Structure(){
        $table = $this->table;
        $db = Yii::$app->db;
        $result = $db->createCommand("SHOW CREATE TABLE `{$table}`")->queryAll();
        $sql  = "\n";
        $sql .= "-- -----------------------------\n";
        $sql .= "-- Table structure for `{$table}`\n";
        $sql .= "-- -----------------------------\n";
        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sql .= trim($result[0]['Create Table']) . ";\n\n";
        if(false === $this->write($sql)){
            return false;
        }else{
            return true;
        }
    }
    /**
     * 备份表结构
     * @param  string  $table 表名
     * @param  integer $start 起始行数
     * @return boolean        false - 备份失败
     */
    public function backup($table, $start){
        //创建DB对象
        $db = Yii::$app->db;
        //备份表结构
        $this->start= $start;
        $this->table = $table;
        if(0 == $start){
            $trl = $this->Structure();
            if($trl==false){
                return false;
            }
        }
        //数据总数
        $result = $db->createCommand("SELECT COUNT(*) AS count FROM `{$table}`")->queryAll();
        $count  = $result['0']['count'];
        //备份表数据
        if($count){
            //写入数据注释
            if(0 == $start){
                $sql  = "-- -----------------------------\n";
                $sql .= "-- Records of `{$table}`\n";
                $sql .= "-- -----------------------------\n";
                $this->write($sql);
            }
            //备份数据记录
            $result = $db->createCommand("SELECT * FROM `{$table}` LIMIT {$start}, 500")->queryAll();
            $sql = "INSERT INTO `{$table}` (";
            $Field = $db->createCommand("DESC `{$table}`")->queryAll();
            foreach($Field as $key=> $vo){
                $sql.="`".$vo['Field']."`,";
            }
            $sql = rtrim($sql,',').")";
            $sql.=" VALUES ";

            foreach ($result as $row) {
                $row = array_map('addslashes', $row);
                $sql .= "('" . str_replace(array("\r","\n"),array('\r','\n'),implode("', '", $row)) . "'),";
            }
            $sql = rtrim($sql,',');
            $sql.=";\n";
            if(false === $this->write($sql)){
                return false;
            }
            //还有更多数据
            if($count > $start + 500){
                return array($start + 500, $count);
            }
        }

        //备份下一表
        return 0;
    }

    public function import($start){
        //还原数据
        $db = Yii::$app->db;
        if($this->config['compress']){
            $gz   = gzopen($this->file[1], 'r');
            $size = 0;
        } else {
            $size = filesize($this->file[1]);
            $gz   = fopen($this->file[1], 'r');
        }
        //$contents = fread($gz, $size); echo $contents;exit;

        $sql  = '';
        if($start){
            $this->config['compress'] ? gzseek($gz, $start) : fseek($gz, $start);
        }
        $i=0;
        while (! feof($gz)) {//判断是否已经达到文件底部
            $sql .=  $this->config['compress'] ? gzgets($gz) : fgets($gz);
            if(preg_match('/.*;$/', trim($sql))){
                $i++;
               if(false !== $db->createCommand($sql)->execute()){
                    $start += strlen($sql);
                } else {
                    return false;
                }
                $sql = '';
                if($i>15){
                    break;
                }
            }elseif(preg_match('/.*Table structure for/', trim($sql))){
                $start += strlen($sql)+strlen('-- -----------------------------');
                 break;
            }elseif ($this->config['compress'] ? gzeof($gz) : feof($gz)) {
                return 0;
            }
        } //echo $sql;exit;
/*        echo $sql;

        for($i = 0; $i < 50; $i++){// print_r(fgets($gz));echo '<br>';
            $sql .= $this->config['compress'] ? gzgets($gz) : fgets($gz);
            if(preg_match('/.*;$/', trim($sql))){
                //DROP TABLE IF EXISTS

                if(false !== $db->createCommand($sql)->execute()){
                    $start += strlen($sql);
                } else {
                    return false;
                }
                $sql = '';
            } elseif ($this->config['compress'] ? gzeof($gz) : feof($gz)) {
                return 0;
            }
        }*/

        $data = array($start, $size);
        return $data;
    }

    /**
     * 析构方法，用于关闭文件资源
     */
    public function __destruct(){
        $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
    }
}