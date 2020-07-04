<?php
namespace mobile\controllers;


use mobile\models\LoginForm;
use Yii;

use yii\helpers\Url;

class PublicController extends BaseController {

    public function actions(){
        return [

            'error' => [
                'class' => 'yii\web\ErrorAction',
                //'layout'=>'_main',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //最大显示个数
                'maxLength' => 4,
                //最少显示个数
                'minLength' => 4,
                //间距
                'padding' => 1,
                //高度
                'height' => 50,
                //宽度
                'width' => 120,

            ],

        ];

    }



    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 后台登录
     */
    public function actionLogin() {


        if (!Yii::$app->user->isGuest) return $this->goHome();

        $model = new LoginForm();
        $model->scenario = 'login';
        if ($model->load(Yii::$app->request->post()) && $uid = $model->login()){
            $url = Yii::$app->getUser()->getReturnUrl();
            $this->success('登录成功',$url);
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }



    /**
     * 退出登录
     */
    public function actionLogout() {

        Yii::$app->user->logout();
        return $this->goHome();
    }

}
