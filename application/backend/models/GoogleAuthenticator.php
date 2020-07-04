<?php
namespace backend\models;
use common\models\GoogleAuthenticator as Common;
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
class GoogleAuthenticator extends Common
{

    public function rules()
    {
        return [
            [['status', 'created_at','updated_at'], 'integer'], //'uid',
            [['username','secretkey'], 'string', 'max' => 50],
            [['username'], 'unique'],
            [['secretkey'], 'match' , 'pattern'=>'/^[0-9a-zA-Z]+$/','message'=>'必须是英文字母或数字'],
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

    public function googleCode($uid,$secretkey){

        $model = new GoogleAuthenticator();
        $vo = $model::find()
            ->where("id=:uid")
            ->addParams([':uid'=>$uid])
            ->asArray()
            ->select('secretkey,username')
            ->one();
        if(!empty($vo)){
            return $vo;
        }
        $info = Admin::find()
            ->where("id=:uid")
            ->addParams([':uid'=>$uid])
            ->asArray()
            ->select('username')
            ->one();
        $model->id          = $uid;
        $model->username    = $info['username'];
        $model->secretkey   = $secretkey;
        $model->status      = 0;
        $model->save();
        return ['secretkey'=>$secretkey,'username'=>$info['username']];
    }
}
