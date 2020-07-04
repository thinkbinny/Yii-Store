<?php
/**
 *
 */
namespace backend\models\article;
use backend\models\Category;
use Yii;


class SinglePage extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName() {
        return '{{%single_page}}';
    }

    /**
     * @return array
     */
    public function rules() {
        return [
            [['title'],'required'],
            [['id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['content','description'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'id'            => 'id',
            'title'         => '标题',
            'description'   => '介绍',
            'content'       => '内容',
        ];
    }
    /*public function beforeValidate(){
        if(parent::beforeValidate()){
            $this->updated_at   = time();

            return true;
        }else{
            return false;
        }
    }*/

}
