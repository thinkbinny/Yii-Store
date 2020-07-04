<?php
namespace common\models;

use Yii;



class UploadGroup extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload_group}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid','sort', 'created_at','updated_at'], 'integer'],
            ['type', 'string', 'max' => 10],
            ['name', 'string', 'max' => 20],
            ['sort', 'default', 'value' =>50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => '分组ID',
            'uid'           => '分组UID',
            'type'          => '分组类型',
            'name'          => '分组名称',
            'sort'          => '分组顺序',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
        ];
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/9 21:47
     */
    public function getType(){
        return [
            'image' => '图片文件'
        ];
    }
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/9 21:47
     */
    public function getTypeText(){
        $data = $this->getType();
        return $data[$this->type];
    }

}
