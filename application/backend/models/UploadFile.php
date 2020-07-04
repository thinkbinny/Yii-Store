<?php
namespace backend\models;

use Yii;
use common\models\UploadFile as common;
use yii\behaviors\TimestampBehavior;


class UploadFile extends common
{



    public function rules()
    {
        return [
            ['name','trim'],
            [['id','uid','group_id','file_size','is_delete', 'created_at','updated_at'], 'integer'],
            [['storage','file_type','file_ext'], 'string', 'max' => 20],
            ['file_md5', 'string', 'max' => 32],
            ['file_sha1', 'string', 'max' => 40],
            [['domain','save_name','save_path'], 'string', 'max' => 50],
            [['name','save_url'], 'string', 'max' => 255],
            [['group_id','is_delete'], 'default', 'value' =>0],
        ];
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }

    public function getGroupId(){
        return UploadGroup::getDataText();
    }

    public function getGroupIdText(){
        $data = $this->getGroupId();
        if(isset($data[$this->group_id])){
            return $data[$this->group_id];
        }else{
            return '--';
        }
    }

}
