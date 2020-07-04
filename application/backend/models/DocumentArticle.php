<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%document_article}}".
 *
 * @property string $id
 * @property integer $parse
 * @property string $content
 * @property string $template
 * @property string $bookmark
 */
class DocumentArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['content','GetString'],
            ['template','GetString'],
           // [['content', 'template'], 'required'],
            [['content'], 'string'],
            [['template'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parse' => '内容解析类型',
            'content' => '文章内容',
            'template' => '详情页显示模板',
            'bookmark' => '收藏数',
        ];
    }
    public function GetString($label){
        $this->$label =  (string) $this->$label;
    }
}
