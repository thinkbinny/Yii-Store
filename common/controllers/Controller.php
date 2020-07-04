<?php
namespace common\controllers;
use Yii;
use yii\web\Response;

/**
 * Site controller
 */
class Controller extends \yii\web\Controller
{
    public function init(){
        parent::init();
    }
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,false,$jumpUrl,$ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,true,$jumpUrl,$ajax);
    }
    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    protected function ajaxReturn($data,$type='') {
        if(empty($type)) $type  =   'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('content-type:application/'.Response::FORMAT_JSON.';charset=utf-8');
                exit(json_encode($data));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';
                exit($handler.'('.json_encode($data).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default     :
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
        }
    }
    /**
     * 最终跳转处理
     * @param  $msg //提示信息
     * @param  $jumpurl //跳转url
     * @param  $wait //等待时间
     * @param int $type 消息类型 0或1
     */
    protected function dispatchJump($message="",$status=1,$jumpUrl="",$ajax=false){
        if(true === $ajax || Yii::$app->request->isAjax) {// AJAX提交
            $data               =   is_array($ajax)?$ajax:array();
            $data['message']    =   $message;
            $data['status']     =   $status;
            $data['url']        =   $jumpUrl;
            $this->ajaxReturn($data);
        }
        $msgTitle= ($status==1) ? Yii::t('common','Prompt Message'): Yii::t('common','Error Message');
        //if(is_int($ajax)) $waitSecond = $ajax;
        if($status==1)  $waitSecond = 1;
        if(!isset($waitSecond))  $waitSecond = 3;
        if(!isset($jumpUrl)) $jumpUrl = $_SERVER["HTTP_REFERER"];
        if(!isset($jumpUrl)) $jumpUrl = 'javascript:window.close();';
        if(empty($jumpUrl)) $jumpUrl = 'javascript:history.back(-1);';
        //$jumpUrl = isset($_SERVER["HTTP_REFERER"])?$jumpUrl:"javascript:window.close();";
        $info = [
            'message'   =>      $message,
            'waitSecond'=>      $waitSecond,
            'msgTitle'  =>      $msgTitle,
            'jumpUrl'   =>      $jumpUrl,
            'status'    =>      $status,
        ];
        return $this->renderpartial(Yii::$app->params['dispatch_jump'],['info'=>$info]);
    }
}
