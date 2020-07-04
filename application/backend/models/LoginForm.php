<?php
namespace backend\models;
use Yii;
use yii\base\Model;
use yii\captcha\CaptchaValidator;
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;
    public $captcha;
    private $_user;

    public function rules() {
        return [
            // username and password are both required
            [['username', 'password','verifyCode','captcha'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['verifyCode', 'validateVerifyCode'],
            ['password', 'validatePassword'],
            ['captcha','integer'],
            ['captcha','validateCaptcha']


        ];
    }
    private function GoogleAuthenticator($uid){
        $Auth = GoogleAuthenticator::find()
            ->where("id=:id AND status=:status")
            ->addParams([':id' => $uid,':status'=>1])
            ->select("secretkey")
            ->asArray()
            ->one();
        if(empty($Auth)){
            return false;
        }else{
            return $Auth;
        }
    }
    /** 谷歌验证
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/9/4 22:49
     */
    public function validateCaptcha($attribute){
        if(empty($this->username)){
            $this->addError($attribute, '您还没有登陆');
            return false;
        }
        $GoogleAuthenticator = new \common\widgets\PHPGangsta\GoogleAuthenticator();
        $model = $this->getUser();
        $auth  = $this->GoogleAuthenticator($model->id);
        if($auth === false){
            $this->addError($attribute, '还没有开启GoogleAuthenticator 认证，无法登陆');
            return false;
        }
        $checkResult = $GoogleAuthenticator->verifyCode($auth['secretkey'], $this->captcha, 2);
        if (!$checkResult) {
            $this->addError($attribute, '您输入的谷歌二次验证码不正确');
            return false;
        }
    }
    /** 验证码验证
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/9/4 22:45
     */
    public function validateVerifyCode($attribute){
        if(empty($this->verifyCode)){
            $this->addError($attribute, '您输入的验证码');
        }else{
            $caprcha = new CaptchaValidator();
            $caprcha->captchaAction = 'public/captcha';
            $list    = $caprcha->validate($this->verifyCode);
            if(empty($list)){
                $this->addError($attribute, '您输入验证码不正确');
            }
        }
    }
    public function attributeLabels() {
        return [
            'username' => '登陆帐号',
            'password' => '登陆密码',
            'verifyCode'=>'验证码',
            'captcha'   => '谷歌二次验证码',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['login'] = ['username','password','verifyCode'];
        $scenarios['auth']  = ['username','captcha'];
        return $scenarios;
    }
    /**
     * google验证
     */
    public function googleValidate(){
        if ($this->validate()) {
            $model = $this->accessSystem();
            return $model;
        } else {
            return false;
        }
    }
    /**
     * 验证密码
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '您输入的用户或密码不正确');//Incorrect username or password.
            }
        }
    }

    /**
     * 使用用户名和密码登录
     * @return boolean
     */
    public function login() {

        //认证
        if ($this->validate()) {
            $model = $this->getUser();
            $auth  = $this->GoogleAuthenticator($model->id);
            if($auth === false){
                $this->accessSystem();
            }
            return $model->id;

        } else {
            return false;
        }
    }

    protected function accessSystem(){
        $model = $this->getUser();
        $isLogin = Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
        //登录成功,记录登录时间和IP
        if($isLogin) {
            $model->last_login_time = time();
            $model->last_login_ip = ip2long(Yii::$app->getRequest()->getUserIP());
            $model->save(false);

            SystemLog::log('账号','登录成功');//登陆
        }
        return $isLogin;
    }

    /**
     * 通过username查找用户
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }
        return $this->_user;
    }



}