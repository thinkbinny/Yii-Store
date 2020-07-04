<?php
namespace backend\models;
use common\libs\Tree;
use common\models\AppsMethod as common;
use Yii;
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
class AppsMethod extends common
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['method', 'trim'],
            [['method','auth','type','sort'], 'required'],
            ['apps_menu_id','required','message' => '请选择{attribute}'],
            [['apps_menu_id','auth','type','sort','status','created_at','updated_at'], 'integer'],
            [['method'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 200],
            [['request','result'], 'safe'],
            // "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
            [['method'],'match','pattern'=>'/^[a-z]+(\.+)+[a-z]+(\.+)+[a-z.]*$/','message'=>'{attribute}格式不正确'],
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
     * @return array
     */
    public function getAuth(){
        return [
         0  =>  '不需要',
         1  =>  '认证',
        ];
    }
    public function getAuthText(){
        $info = $this->getAuth();
        return $info[$this->auth];
    }

    /**
     * @return array
     */
    public function getType(){
        return [
            0  =>  '免费',
            1  =>  '收费',
        ];
    }
    public function getTypeText(){
        $info = $this->getType();
        return $info[$this->type];
    }

    public function getAppsMenuId(){
        $volsit = AppsMenu::find()
            ->where('status=:status')
            ->addParams([':status'=>1])
            ->select('id,pid,name')
            ->asArray()
            ->all();
        $treeObj = new Tree($volsit);
        return $treeObj->getTree();
    }
    /**
     *
     */
    public function getAppsMenuIdText(){
        $k      = 'getAppsMenuText';
        $data   = Yii::$app->cache->get($k);
        if(empty($data)){
            $data   = array();
            $volsit = AppsMenu::find()
                ->select('id,name')
                ->asArray()
                ->all();
            foreach ($volsit as $val){
                $data[$val['id']] = $val['name'];
            }
            Yii::$app->cache->set($k,$data,86000);
        }
        if(!isset($data[$this->apps_menu_id])){
            return null;
        }else{
            return $data[$this->apps_menu_id];
        }
    }
}
