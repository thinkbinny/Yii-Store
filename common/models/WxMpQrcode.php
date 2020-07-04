<?php
namespace common\models;

use Yii;

/**
 * Class WxUser
 * @package common\models
 * @Author thinkbinny<274397981@qq.com>
 * @Date 2019/1/23 14:56
 */
class WxMpQrcode extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_mp_qrcode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['valid_days','valid_time','scans_count'], 'integer'],
            [['title','callback'], 'string', 'max' => 50],
            [['scene'], 'string', 'max' => 64],
            [['pic_url'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '自动ID',
            'scene'             => '场景值',
            'title'             => '场景备注',
            'callback'          => '回调方法',
            'valid_days'        => '有效期',
            'valid_time'        => '有效时间',
            'pic_url'           => '二维码',
            'scans_count'       => '扫描次数',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',

        ];
    }



}
