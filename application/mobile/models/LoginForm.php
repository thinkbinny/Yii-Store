<?php
namespace mobile\models;
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



        ];
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

        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

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

            return $model->id;

        } else {
            return false;
        }
    }

    protected function accessSystem(){
        $model = $this->getUser();
        $isLogin = Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
        //登录成功,记录登录时间和IP
        /*if($isLogin) {

        }*/
        return $isLogin;
    }

    /**
     * 通过username查找用户
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = Member::findByUsername($this->username);
        }
        return $this->_user;
    }



}