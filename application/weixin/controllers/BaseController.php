<?php
namespace weixin\controllers;
use weixin\models\User;
use weixin\models\WxUser;
use Yii;
use yii\helpers\Url;
use common\controllers\Controller;
use yii\filters\VerbFilter;
use weixin\components\AccessControl;
use common\models\Config;

class BaseController extends Controller {
    protected $uid;
    protected $openid;
    protected $wx_temp_id;
    protected $getUserInfo;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),

                'rules' => [
                    [
                        'actions' => ['login', 'error','rest','oauth','view'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','rest'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**/
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * 初始化配置信息
     * 网站配置或模板配置等
     */
    public function init() {
        parent::init();
        Yii::$app->params['basic'] = Config::getConfigs('basic');
        Yii::$app->params['weixin'] = Config::getConfigs('weixin');
        $this->getUserInfo();
        return true;
    }
    /**
     * 获取登陆信息
     */
    protected function getUserInfo(){
        $user = Yii::$app->session->get(Yii::$app->user->idParam);
        //如果没有绑定每次访问都在查询数据库
        if(isset($user['uid'])) {
            if (empty($user['uid'])) {
                $wx_user = WxUser::find()
                    ->where("openid=:openid")
                    ->addParams(['openid'=>$user['openid']])
                    ->asArray()
                    ->one();
                if(isset($wx_user['uid']) && !empty($wx_user['uid'])){
                    $user = $wx_user;
                    Yii::$app->session->set(Yii::$app->user->idParam,$wx_user);
                }
            }
        }

        if(!empty($user)){
            $this->uid          = $user['uid'];
            $this->openid       = $user['openid'];
            $this->getUserInfo  = $user;
        }
        return $user;
       /* $this->openid     = 'ozvPHjozmEXoL4pJkSxYsR54D8WM';
        $this->wx_temp_id = 0;*/
    }

}