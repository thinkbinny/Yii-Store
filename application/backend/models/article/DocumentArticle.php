<?php
/**
 *
 */
namespace backend\models\article;
use backend\models\Category;
use Yii;


class DocumentArticle extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName() {
        return '{{%document_article}}';
    }

    /**
     * @return array
     */
    public function rules() {
        return [
            //[['id'],'required'],
            [['template'], 'string', 'max' => 100],
            [['content'], 'safe'],
            [['template'], 'default', 'value' => ''],

        ];
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'id'            => 'id',
            'template'      => '显示模板',
            'content'       => '内容',
        ];
    }

}
