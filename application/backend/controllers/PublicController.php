<?php
namespace backend\controllers;
use backend\models\GoogleAuthenticator;
use backend\models\Region;
use backend\models\SystemLog;
use Yii;
use backend\models\LoginForm;
use yii\helpers\Url;

class PublicController extends BaseController {
    public function actions(){
        return [

            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout'=>'_main',
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
            //地区
            'region'=>[
                'class'=>\backend\components\relation\RegionAction::className(),
                'model'=>Region::className(),
            ]
        ];

    }



    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 后台登录
     */
    public function actionLogin() {
        $this->layout = '_main';
        if (!Yii::$app->user->isGuest) return $this->goHome();

        $model = new LoginForm();
        $model->scenario = 'login';
        if ($model->load(Yii::$app->request->post()) && $uid = $model->login()){
            if (!Yii::$app->user->isGuest){
                $url = Yii::$app->getUser()->getReturnUrl();
                $this->success('登录成功',$url);
            }else{
                Yii::$app->session->set('loginUserName',$model->username);
                $url = Url::to(['authenticator']);
                $this->success('进入二次验证',$url);
            }
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 谷歌验证
     */
    public function actionAuthenticator(){
        $this->layout = '_main';
        $username = Yii::$app->session->get('loginUserName');
        if(empty($username)){
            return $this->redirect(['login']);
        }
        $model = new LoginForm();
        $model->scenario = 'auth';
        $model->username = $username;
        if ($model->load(Yii::$app->request->post()) && $model->googleValidate()){
            Yii::$app->session->remove('loginUserName');
            $url = Yii::$app->getUser()->getReturnUrl();
            $this->success('谷歌验证通过',$url);
        }else{
            return $this->render('authenticator',[
                'model' =>$model,
            ]);
        }
    }

    /**
     * 退出登录
     */
    public function actionLogout() {
        SystemLog::log('账号','退出登录');
        Yii::$app->user->logout();
        return $this->goHome();
    }
    /**
     * 清除缓存
     */
    public $enableCsrfValidation = false;
    public function actionCache(){
        Yii::$app->cache->flush();
        if(Yii::$app->request->isAjax){
            $this->success('清除缓存完成');
        }else{
            return $this->goBack();
        }
    }

    public function actionMap(){
        $this->layout =false;
        return $this->render('map',[

        ]);
    }
}
