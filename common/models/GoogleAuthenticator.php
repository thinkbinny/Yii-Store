<?php
namespace common\models;

use Yii;



class GoogleAuthenticator extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%google_authenticator}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status' ,'created_at','updated_at'], 'integer'],//'uid',
            [['username','secretkey'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            //'uid'           => '用户UID',
            'username'      => '用户名称',
            'secretkey'     => '安全密匙',
            'status'        => '状态',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
        ];
    }
}
