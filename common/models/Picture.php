<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%picture}}".
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picture}}';
    }
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','file_id', 'folder_id', 'size', 'filetype', 'created_at', 'updated_at','status'], 'integer'],
            [['name'], 'string', 'max' =>50],
            [['mime'], 'string', 'max' =>40],
            [['pic_url'], 'string', 'max' => 250],
            ['file_id','default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'uid'       => '用户uid',//0表示后台
            'folder_id' => '文件目录',
            'name'      => '图片名称',
            //'path'      => '路径',
            'pic_url'   => '图片地址',
            'mime'      => 'Mime类型', //文件mime类型
            'size'      => '文件大小',
            'filetype'  => '文件类型',
            'created_at'=> '创建时间',
            'updated_at'=> '更新时间',
            'status'    => '状态',

        ];
    }

    //\
    public function folder($id,$str = ''){
        $info = $this->find()
            ->where('id=:id AND filetype=:filetype')
            ->addParams([':id'=>$id,':filetype'=>0])
            ->select('id,name,folder_id')
            ->asArray()
            ->one();
        if(!empty($info['folder_id'])){
            $str .= $info['id'].'/';
            return $this->folder($info['folder_id'],$str);
        }else{
            //$data[] = $info['id'];
            $str .= $info['id'];
            return $str;
        }
    }

    //添加文件
    public function CreatePicture($name,$file_id,$folder_id,$uid,$pic_url,$size,$mime){
        $model = new Picture();
        $model->name        = $name;
        $model->file_id     = $file_id;
        $model->folder_id   = $folder_id;
        $model->uid         = $uid;
        $model->pic_url     = $pic_url;
        $model->size        = $size;
        $model->mime        = $mime;
        $model->filetype    = 1;
        $model->status      = 1;
        if($model->validate()){
            $model->save();
            return \Yii::$app->db->getLastInsertID();
        }else{
            return false;
        }
    }
}
?>