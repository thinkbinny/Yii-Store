<?php
namespace common\models;

use Yii;

/**
 * Class WxUser
 * @package common\models
 * @Author thinkbinny<274397981@qq.com>
 * @Date 2019/1/23 14:56
 */
class WxMiniQrcode extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_mini_qrcode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['width','created_at','updated_at'], 'integer'],
            [['page','line_color'], 'string', 'max' => 50],
            [['scene'], 'string', 'max' => 32],
            [['pic_url'], 'string', 'max' => 255],
            [['auto_color','is_hyaline'], 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => true]

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
            'page'              => '小程序页面',
            'width'             => '宽度',
            'auto_color'        => '线条颜色',
            'line_color'        => '设置颜色',
            'is_hyaline'        => '是否透明',
            'pic_url'           => '二维码',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',

        ];
    }



}
