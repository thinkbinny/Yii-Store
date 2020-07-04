<?php
namespace weixin\controllers;

use common\components\Func;
use common\plugin\picture\models\File;
use ext\ip2location\Ip2Location;
use ext\wx\Application;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class IndexController extends BaseController
{
    /**
     * @inheritdoc
     
    public function behaviors()
    {
        return [

        ];
    }
*/

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    // https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
    // https://api.weixin.qq.com/sns/jscode2session?appid=wxf6ad8df6cfe2e1b7&secret=17b10cefdbf5e06748c1919135e112a3&js_code=023mUbLh2JGizI0ObtMh2r0qLh2mUbLB&grant_type=authorization_code
    public function actionIndex()
    {
        return $this->redirect(['integral/index']);
    }


}
