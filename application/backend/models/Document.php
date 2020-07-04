<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%document}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property integer $category_id
 * @property string $description
 * @property string $root
 * @property string $pid
 * @property integer $model_id
 * @property integer $type
 * @property integer $position
 * @property string $link_id
 * @property string $cover_id
 * @property integer $display
 * @property string $deadline
 * @property integer $attach
 * @property string $view
 * @property string $comment
 * @property string $extend
 * @property string $sort
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property string $keyword
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['display','default','value'=>0],
            ['sort','default','value'=>0],
            ['view','default','value'=>0],
            ['status','default','value'=>1],
            ['link_id','default','value'=>0],
            ['deadline','default','value'=>0],
            ['comment','default','value'=>0],
            ['position','default','value'=>0],
            ['cover_id','default','value'=>0],
            ['deadline','default','value'=>0],
            ['created_at','default','value'=>time()],
            ['updated_at','default','value'=>time()],
            ['updated_at','GetUpdate'],
            ['position','GetPosition'],
            ['deadline','ConversionTimestamp'],
            [['uid', 'category_id', 'root', 'pid', 'model_id', 'type', 'position', 'link_id', 'cover_id', 'display', 'attach', 'view', 'comment', 'extend', 'sort', 'created_at', 'updated_at', 'status'], 'integer'],
            [[ 'title', 'keywords', 'description', 'category_id'], 'required'],
            [['title'], 'string', 'max' => 120],
            [['keywords'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 200],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'uid'           => '用户UID',
            'title'         => '标题',
            'keywords'      => '关键字',
            'category_id'   => '分类',
            'description'   => '描述',
            'root'          => '根节点',
            'pid'           => '所属ID',
            'model_id'      => '内容模型ID',
            'type'          => '内容类型',
            'position'      => '推荐位',
            'link_id'       => '外链',
            'cover_id'      => '封面',
            'display'       => '可见性',
            'deadline'      => '截至时间',
            'attach'        => '附件数量',
            'view'          => '浏览量',
            'comment'       => '评论数',
            'extend'        => '扩展',
            'sort'          => '顺序',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
            'status'        => '状态',
            'keyword'       => '关键字',
        ];
    }

    /**
     * @param $label
     */
    public function GetPosition($label){
        $position = $this->$label;
        $pos = 0;
        if(!is_array($position)){
            $pos = 0;
        }else{
            foreach ($position as $key=>$value){
                $pos += $value;		//将各个推荐位的值相加
            }
        }
        $this->$label = $pos;
    }

    /**
     * @param $label
     */
    public function ConversionTimestamp($label){
        if(empty($this->$label)){
            $this->$label = 0;
        }else{
            $this->$label = strtotime($this->$label);
        }
    }

    /**
     * @param $label
     * 时间
     */
    public function GetUpdate($label){
        $this->$label=time();
    }


}
