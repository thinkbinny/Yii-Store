<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace extensions\uploads\driver;
//use yii\imagine\Image;
use Yii;
class Local{
    /**
     * 上传文件根目录
     * @var string
     */
    private $rootPath;

    /**
     * 本地上传错误信息
     * @var string
     */
    private $error = ''; //上传错误信息

    private $config;
    /**
     * 构造函数，用于设置上传根路径
     */
    public function __construct($config = null){
        $this->config = $config;
    }

    /**
     * 检测上传根目录
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath){

        if(!(is_dir($rootpath) && is_writable($rootpath))){
            $this->error = '上传根目录不存在！请尝试手动创建:'.$rootpath;
            return false;
        }
        $this->rootPath = $rootpath;
        return true;
    }

    /**
     * 检测上传目录
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        /* 检测并创建目录 */
        if (!$this->mkdir($savepath)) {
            return false;
        } else {
            /* 检测目录是否可写 */
            if (!is_writable($this->rootPath . $savepath)) {
                $this->error = '上传目录 ' . $savepath . ' 不可写！';
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * 保存指定文件
     * @param  array   $file    保存的文件信息
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     */
    public function save($file, $replace=true) {
        $filename = $this->rootPath . $file['savepath'] . $file['savename'];

        /* 不覆盖同名文件 */ 
        if (!$replace && is_file($filename)) {
            $this->error = '存在同名文件' . $file['savename'];
            return false;
        }

        /* 移动文件 */
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            $this->error = '文件上传保存错误！';
            return false;
        }

        //添加水印
        if(isset($this->config['watermark'])){
            if($this->config['watermark'] == true){
                $watermarkFilename  =  $this->config['watermarkImg'];
                $position           =  $this->config['watermarkPosition'];
                $img = imagecreatefrompng($filename);
                $w = imagesx( $img );
                $h = imagesy( $img );
                $yin_img    = imagecreatefrompng($watermarkFilename);
                $width      = imagesx( $yin_img );
                $height     = imagesy( $yin_img );



                $right      = $w - ($width + 10);
                $bottom     = $h - ($height + 10);
                if($right<0){ $right = 0;}
                if($bottom<0){ $bottom = 0;}


                switch ($position){
                    case 1:
                        $position = [10,10];
                        break;
                    case 2:
                        $position = [$right,10];
                        break;
                    case 3:
                        $position = [10,$bottom ];
                        break;
                    case 4:
                        $position = [$right,$bottom ];
                        break;

                }
                //@chmod($filename,0777);
                //Image::watermark($filename,$watermarkFilename,$position)->save($filename, ['quality' => 100]);
            }
        }

        return true;
    }

    /**
     * 创建目录
     * @param  string $savepath 要创建的穆里
     * @return boolean          创建状态，true-成功，false-失败
     */
    public function mkdir($savepath){
        $dir = $this->rootPath . $savepath;
        if(is_dir($dir)){
            return true;
        }
        if(mkdir($dir, 0777, true)){
            return true;
        } else {
            $this->error = "目录 {$savepath} 创建失败！";
            return false;
        }
    }

    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError(){
        return $this->error;
    }
    protected function DeleteDir($filename){
        $dirname = dirname($filename);
        $uploads = basename($dirname);
       if($uploads=='uploads'){
           return true;
       }
        $file=scandir($dirname);

        if(!isset($file[2])){
            @rmdir($dirname);
        }
    }
    public function delete($filename)
    {

        //$this->DeleteDir($filename);
        if ( file_exists($filename)  ) {

            //删除命令
            if (unlink($filename))
            {
                $this->DeleteDir($filename);
                return true;
            }
            else
            {
                $this->error = "删除文件失败";
                return false;
            }
        }else
        {
            $this->error = "文件不存在";
            return false;
        }
    }
}
