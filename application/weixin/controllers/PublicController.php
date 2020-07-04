<?php
namespace weixin\controllers;
use extensions\weixin\Application;
use weixin\models\User;
use Yii;
use yii\helpers\Url;
use common\models\Config;
/**
 * Public controller
 */
class PublicController extends BaseController
{


    public function behaviors()
    {

    }

    /**微信受权登陆**/
    public function actionLogin(){
        $app    = new Application();
        $weixin = $app->driver("mp.oauth");
        $weixin->send();
    }

    public function actionOauth(){
        $app    = new Application();
        $weixin = $app->driver("mp.oauth");
        $result = $weixin->user();
        if(isset($result['openid'])){
            /**
             * 登陆用户
             */
            User::login($result);
            $redirectURL = Yii::$app->session->getFlash('redirectURL');
            if(empty($redirectURL)){
                $redirectURL =  Url::to(['index/index'],true);
            }
            //echo $redirectURL;exit;
            return $this->redirect($redirectURL);
        }else{
            $this->error('受权失败');
        }
    }
    public function actionLogout(){
        Yii::$app->session->destroy();
        //Yii::$app->session->destroySession(Yii::$app->user->idParam);
        //return $this->redirect(['index/index']);
    }
    public $enableCsrfValidation = false;
    //微信通知页面 通知地址
    public function actionWxdayinpay(){
        echo Url::to(['']);
    }

    /**
     * 生成二维码
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/25 8:06
  
    public function actionQrcode(){

        $app    = new Application();
       $weixin = $app->driver("mp.qrcode");
        $str    = 'mini_uid_1';
        $weixin->strTemp($str);
        echo $weixin->getQrcode();
    }   */
    public function actionTest(){
        $data = '';
        print_r(json_decode($data));
    }

}
?>