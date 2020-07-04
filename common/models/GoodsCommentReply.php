<?php
namespace common\models;

use Yii;



class GoodsCommentReply extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_comment_reply}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'],'trim'],
            [['comment_id','reply_id','reply_type','content','from_uid'],'required'],
            [['id','comment_id','reply_id','reply_type','from_uid','to_uid','content_time'], 'integer'],
            [['content'], 'string', 'max' => 250],
            [['reply_type'], 'in', 'range' => [1, 2]],//回复类型(1评论,2回复)
            [['from_uid','to_uid'], 'default', 'value' => 0],
            [['content_time'], 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'id'                => '回复ID',
            'comment_id'        => '评论id',
            'reply_id'          => '回复目标id',
            'reply_type'        => '回复类型',//(1评论,2回复)
            'content'           => '评论内容',//(可拆分)
            'from_uid'          => '回复用户id',//(增加积分、减少积分)
            'to_uid'            => '目标用户id',
            'content_time'      => '评论时间',

        ];
    }




}
