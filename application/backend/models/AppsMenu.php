<?php
namespace backend\models;
use common\models\AppsMenu as common;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%File}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string  $title
 * @property string  $url
 * @property string  $remark
 * @property integer $status
 * @property integer $sort
 */
class AppsMenu extends common
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    public static $displays = [
        self::STATUS_ACTIVE => '正常',
        self::STATUS_DELETED => '禁止',
    ];
    /**
     * @return array
     */
    public function getStatus() {
        return self::$displays;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            ['name', 'trim'],
            [['name','pid'],'required'],
            [['pid','sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 250],
            ['sort', 'default', 'value' => 50],
            ['status', 'default', 'value' => 1],
        ];
    }
    /**
     * @inheritdoc
     */

}
