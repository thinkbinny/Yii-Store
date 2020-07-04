<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class UploadFile extends \yii\db\ActiveRecord{

    const CACHE_KEY_IMAGE_URL = 'cache_key_image_url_list';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload_file}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid','group_id','file_size','is_delete', 'created_at','updated_at'], 'integer'],
            [['storage','file_type','file_ext'], 'string', 'max' => 20],
            ['file_md5', 'string', 'max' => 32],
            ['file_sha1', 'string', 'max' => 40],
            [['domain','save_name','save_path'], 'string', 'max' => 50],
            [['name','save_url'], 'string', 'max' => 255],
            [['group_id','is_delete'], 'default', 'value' =>0],
        ];
    }
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => '文件ID',
            'uid'           => '用户UID',
            'storage'       => '存储方式',
            'group_id'      => '分组ID',
            'name'          => '文件名称',
            'save_url'      => '存储URL',
            'domain'        => '存储域名',//如果是空为本地
            'save_name'     => '存储名称',
            'save_path'     => '存储路径',
            'file_size'     => '文件大小',
            'file_type'     => '文件类型',
            'file_ext'      => '文件后缀',
            'file_md5'      => '文件md5',
            'file_sha1'     => '文件sha1',
            'is_delete'     => '是否删除',
            'created_at'    => '上传时间',
            'updated_at'    => '更新时间',
        ];
    }

    public static function getImageUrl($id){
        $id = (int)$id;
        if(empty($id)){
            return '';
        }
        static $list;
        $cache_key = self::CACHE_KEY_IMAGE_URL;

        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get($cache_key);
        }
        $key = "f{$id}";
        if(isset($list[$key])){ //已缓存，直接使用
            $pic_url = $list[$key];
        } else {
            $model = UploadFile::find()
                ->where("id=:id")
                ->addParams([':id' => $id])
                ->select('save_url')
                ->asArray()
                ->one();

            if (!empty($model)) {
                $pic_url = $list[$key] = $model['save_url'];
                /* 缓存用户 */
                $max   = 10000;
                $count = count($list);
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set($cache_key,$list,2592000);//保存一个月
            }else{
                $pic_url = '';
            }
        }
        return $pic_url;
    }
}
