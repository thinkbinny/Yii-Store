<?php
namespace backend\models;
use Yii;

use common\components\Func;

class SystemLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_log}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','created_at'], 'integer'],
            [['username','realname','action_name'], 'string', 'max' => 50],
            [['action_url'], 'string', 'max' => 200],
            [['action_remark'], 'string', 'max' => 100],
            [['action_log'], 'safe'],
            [['username','realname','action_name','action_remark','action_log'], 'default', 'value' =>''],
            ['action_ip', 'default', 'value' =>Func::get_client_ip(1)],
            ['created_at', 'default', 'value' =>time()],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'uid'           => 'UID',
            'username'      => '操作者',
            'realname'      => '真实姓名',
            'action_name'   => '操作名称',
            'action_url'    => '操作地址',
            'action_remark' => '操作备注',
            'action_log'    => '操作记录',
            'action_ip'     => 'IP地址',
            'created_at'    => '访问时间',

        ];
    }

    public static function log($name,$remark='浏览数据'){
        if(Yii::$app->user->isGuest){
            return false;
        }
        $log                    =   array();
        if(empty($post)){
            $log['POST']        =   $_POST;
            unset($log['POST'][Yii::$app->request->csrfParam]);
        }
        if(empty($get)) {
            $log['GET']         = $_GET;
        }
        $user = Yii::$app->user->identity;
        $model = new SystemLog();
        $model->uid             = $user->id;
        $model->username        = $user->username;
        $model->realname        = $user->realname;
        $model->action_url      = Yii::$app->request->url;

        $model->action_name     = $name;
        $model->action_remark   = $remark;
        $model->action_log      = json_encode($log);;
        if($model->validate()){
            return $model->save();
        }else{
            return false;
        }
    }
}
