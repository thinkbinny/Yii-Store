<?php
namespace backend\models;

use common\components\Func;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%Links}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string  $title
 * @property string  $url
 * @property string  $remark
 * @property integer $status
 * @property integer $sort
 */
class Slides extends \yii\db\ActiveRecord
{

    const DISPLAY = 1;
    const HIDE = 0;
    public static $typeList = [
        1 => 'PC',
        2 => '手机',
    ];
    public static $displays = [
        self::DISPLAY => '显示',
        self::HIDE => '隐藏',
    ];

    public static $displayStyles = [
        self::HIDE => 'label-warning',
        self::DISPLAY => 'label-info',
    ];

    public function __construct() {
        $this->status = self::DISPLAY;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slides}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','title','image_id','url'], 'required'],
            [['type','image_id','thumb_image_id', 'sort', 'status','created_at','updated_at'], 'integer'],
            [['title','url'], 'string', 'max' => 200],
            ['type', 'default', 'value' => 1],
            ['sort', 'default', 'value' => 50],
            ['status', 'default', 'value' => 1],
            ['thumb_image_id', 'default', 'value' => 0],
            ['url', 'url', 'defaultScheme' => 'http'],//,'message'=>'网站地址不是一条有效的http://地址。'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'type'              => '类型',
            'title'             => '幻灯片名称',
            'image_id'          => '主图片',
            'thumb_image_id'    => '副图片',
            'url'               => '网址',
            'status'            => '状态',
            'sort'              => '排序',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
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

    /**
     * @return array
     */
    public function getType(){
        return self::$typeList;
    }
    /**
     * @return array
     */
    public function getStatus() {
        return self::$displays;
    }

    /**
     * 获取菜单状态
     */
    public static function getStatusText($display) {
        return self::$displays[$display];
    }

    /**
     * 获取类型状态
     */
    public static function getTypeText($type) {
        return self::$typeList[$type];
    }

    public function getImageUrl(){
        return Func::getImageUrl($this->image_id);
    }


}
