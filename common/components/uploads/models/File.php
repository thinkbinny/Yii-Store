<?php
namespace extensions\uploads\models;
use Yii;
use extensions\uploads\services\Uploads;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property string $id
 * @property integer $uid
 * @property string $name
 * @property string $savename
 * @property string $savepath
 * @property string $ext
 * @property string $mime
 * @property string $size
 * @property string $md5
 * @property string $sha1
 * @property integer $location
 * @property string $url
 * @property string $created_at
 */
class File extends \common\models\UploadFile
{
    /**
     * @inheritdoc
     */
    public $error;
    /**
     * @var File attribute
     */
    public $file;


    /**
     * @param $files
     * @param $setting
     * @param string $driver
     * @param null $config
     * @return array|bool
     */
    public function upload($files, $setting, $driver = 'Local', $config = null){
        /* 上传文件 */
        $setting['callback']    = array($this, 'isFile');
        $setting['removeTrash'] = array($this, 'removeTrash');
        $domain = '';
        if(isset($setting['driverConfig']['domain'])){
            $domain = $setting['driverConfig']['domain'];
        }

        $group_id = $setting['group_id'];
        if($group_id<0){
            $group_id = 0;
        }

        $Upload = new Uploads($setting, $driver, $config);
        $info   = $Upload->upload($files);
        $model = new File();
        /* 设置文件保存位置 */
        if($info){ //文件上传成功，记录文件信息
            $data = array();
            foreach ($info as $key => &$value) {
                if(isset($value['id'])){
                    $data['id']         = $value['id'];
                    $data['pic_url']    = $value['save_url'];
                    $data['name']       = $value['name'];
                    if($value['is_delete']==1){
                        $model::updateAll(['is_delete'=>0,'group_id'=>$setting['group_id']],'id=:id',[':id'=>$data['id']]);
                    }else{
                        $model::updateAll(['group_id'=>$setting['group_id']],'id=:id',[':id'=>$data['id']]);
                    }
                }else{
                    $model->uid         = $setting['uid']; //用户UID
                    $model->storage     = $driver; //存储方式
                    $model->group_id    = $group_id; //分组ID
                    $model->name        = $value['name']; //文件名称
                    $model->domain      = $domain; //存储域名 本地为空
                    $model->save_url    = $domain . $setting['rootPath'] . $setting['savePath'] . $value['savename']; //存储URL
                    $model->save_name   = $value['savename']; //存储名称
                    $model->save_path   = $setting['rootPath'] . $value['savepath']; //存储路径
                    $model->file_size   = $value['size']; //文件大小
                    $model->file_type   = 'image'; //文件类型
                    $model->file_ext    = $value['file_ext']; //文件后缀
                    $model->file_md5    = $value['file_md5']; //文件md5
                    $model->file_sha1   = $value['file_sha1']; //文件sha1

                    if($model->validate()){
                        $model->save();
                        $data['id']         = Yii::$app->db->getLastInsertID();
                        $data['pic_url']    = $model->save_url;
                        $data['name']       = $value['name'];
                    }else {
                        //print_r($model->getErrors());exit;
                        //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                        unset($info[$key]);
                    }
                }

            }

          return $data; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['file_md5'])){
            throw new BadRequestHttpException("不存在上传驱动：md5");
        }
        $info = File::find()
            ->where('file_md5=:file_md5 AND file_sha1=:file_sha1')
            ->addParams([':file_md5'=>$file['file_md5'],':file_sha1'=>$file['file_sha1']])
            ->asArray()
            ->one();
       /* if(!empty($info)){
            if($info['uid']!=$file['uid']){
                return false;
            }
        }*/
       /*if(!empty($info)){
           $this->DeleteFile($info['id']);
       }*/
        /* 查找文件 */
        return $info;
    }
    /**
     * 清除数据库存在但本地不存在的数据
     * @param $data
     */
    public function removeTrash($data){
        File::deleteAll('id=:id',[':id'=>$data['id']]);
    }
    /*
     *查询
     */
    public function GetPicUrl($id=0){
        if(!empty($id)){
            $info = File::find()
                ->where('id=:id')
                ->addParams([':id'=>$id])
                ->select('savepath,savename')
                ->asArray()
                ->one();
            return $info['savepath'].$info['savename'];//$info['path'];
        }else{
            return '';
        }
    }
    /*
     *    出错信息
     */
    public function error(){
        return $this->error;
    }
    /**
     *
     *
     */
    public function DeleteFile($driver,$id)
    {
        $info  = false;
        $model = File::findOne($id);
        $storage    = ucwords($model->storage);
        $driver     = ucwords($driver);
        if($storage == $driver){
            $path       = $model->save_path . $model->save_name;
            $Upload     = new Uploads(array(), $driver, array());
            $basePath   = dirname(dirname(\Yii::$app->basePath)) . "\web\storage";
            $file       =  $basePath.$path;//完整路径

            $info       = $Upload->delete($file);
        }else{
            $this->error = '驱动不支持';
            return false;
        }


        if($info){
           $model->delete();
           return true;
        }else{//print_r($Upload->getError());exit;
            $this->error = $Upload->getError();
            return false;
        }
        //$model
    }
}
?>