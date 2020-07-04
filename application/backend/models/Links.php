<?php
namespace backend\models;

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
class Links extends \yii\db\ActiveRecord
{

    const DISPLAY = 1;
    const HIDE = 0;
    public static $typeList = [
        1 => '首页',
        2 => '频道',
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
        $this->sort = 10;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%links}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','url'], 'required'],
            [['image_id','type', 'status', 'sort','created_at','updated_at'], 'integer'],
            [['title', 'url'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 200],
            [['type','status'], 'default', 'value' => 1],
            ['sort', 'default', 'value' => 50],
            ['remark', 'default', 'value' => ''],
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
            'type'              => '链接类型',
            'title'             => '链接名称',
            'image_id'          => '链接图片',
            'url'               => '网站网址',
            'remark'            => '备注',
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

    //


}
