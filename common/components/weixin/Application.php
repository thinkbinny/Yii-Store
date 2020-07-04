<?php


namespace extensions\weixin;
use extensions\weixin\core\Exception;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;

/**
 * bootstrap
 * 此类负责模块其他类的驱动以及相关变量的初始化
 *
 * @link https://nai8.me/study/yii2weixin.html
 * @author thinkbinny
 * @package extensions\weixin
 */
class Application extends Component {

    /**
     * yii2-weixin配置
     * @var
     */
    public $conf = [];

    /**
     * http客户端
     * @var
     */
    public $httpClient;

    public $httpConf = [
        'transport' => 'yii\httpclient\CurlTransport',
    ];

    /**
     * 类映射
     * @var array
     */
    public $classMap = [
        'core'=>[
            'accessToken'=>'extensions\weixin\core\AccessToken'
        ],
        'mp'=>[
            'accessToken'=>'extensions\weixin\core\AccessToken',
            'base'=>'extensions\weixin\mp\core\Base',          // 获取微信服务器IP或IP段
            'qrcode'=>'extensions\weixin\mp\qrcode\Qrcode',    // 二维码
            'shorturl'=>'extensions\weixin\mp\qrcode\Shorturl',// 短地址
            'server'=>'extensions\weixin\mp\server\Server',    // 服务接口
            'remark'=>'extensions\weixin\mp\user\Remark',      //  会员备注
            'user'=>'extensions\weixin\mp\user\User',          //  会员管理
            'tag'=>'extensions\weixin\mp\user\Tag',            //  会员标签
            'menu'=>'extensions\weixin\mp\menu\Menu',          // 菜单
            'js'=>'extensions\weixin\mp\js\Js',                //  JS
            'template'=>'extensions\weixin\mp\template\Template', //   消息模板
            'pay'=>'extensions\weixin\mp\payment\Pay',         //  支付接口
            'mch'=>'extensions\weixin\mp\payment\Mch',         //  企业付款
            'redbag'=>'extensions\weixin\mp\payment\Redbag',   //  红包
            'oauth'=>'extensions\weixin\mp\oauth\OAuth',       //  web授权
            'resource'=>'extensions\weixin\mp\resource\Resource',//  素材
            'kf'=>'extensions\weixin\mp\kf\Kf',                //  客服
            'customService'=>'extensions\weixin\mp\kf\CustomService',//  群发
        ],
        'mini'=>[
            'user'=>'extensions\weixin\mini\user\User',        // 会员
            'pay'=>'extensions\weixin\mini\payment\Pay',       // 支付
            'qrcode'=>'extensions\weixin\mini\qrcode\Qrcode',  // 二维码&小程序码
            'template'=>'extensions\weixin\mini\template\Template', // 模板消息
            'custom'=>'extensions\weixin\mini\custom\Customer',
            'server'=>'extensions\weixin\mini\custom\Server',
        ]

    ];

    public function init(){
        parent::init();
        $this->httpClient = new Client($this->httpConf);
    }

    /**
     * 驱动函数
     * 此函数主要负责生成相关类的实例化对象并传递相关参数
     *
     * @param $api string 类的映射名
     * @param array $extra  附加参数
     * @throws Exception
     * @return object
     */
    public function driver($api,$extra = []){

        $api = explode('.',$api);
        if(empty($api) OR isset($this->classMap[$api[0]][$api[1]]) == false){
            throw new Exception('很抱歉，你输入的API不合法。');
        }

        //  初始化conf
        if(empty($this->conf)){
            if(isset(Yii::$app->params['weixin']) == false){
                throw new Exception('请在yii2的配置文件中设置配置项weixin');
            }
            if(isset(Yii::$app->params['weixin'][$api[0]]) == false){
                throw new Exception("请在yii2的配置文件中设置配置项weixin[{$api[0]}]");
            }

            $this->conf = Yii::$app->params['weixin'][$api[0]];
        }

        $config = [
            'conf'=>$this->conf,
            'httpClient'=>$this->httpClient,
            'extra'=>$extra,
        ];

        $config['class'] = $this->classMap[$api[0]][$api[1]];

        return Yii::createObject($config);
    }
}