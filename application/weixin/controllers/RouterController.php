<?php
namespace weixin\controllers;
use ext\weixin\Application;
use weixin\models\WxUser;
use weixin\services\Server;
use Yii;
use ext\weixin\mp\message\Image;
/**
 * Router controller
 */
class RouterController extends BaseController
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [

        ];
    }/**/
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionRest()
    {

        //$message = new Image(['props' =>['MediaId'=>'gYcYl5lA1vh5QTkhAkIrYyQu60qjgucX2OxemTgDg8I']]);
        //print_r($message);exit;
        $app    = new Application();
        $weixin = $app->driver("mp.server");
        $weixin -> setMessageHandler(function($message) {
            $server = new Server($message);
            $result = $server->getResult();
            return $result;
        });
        $response = $weixin->serve();
        return $response;
    }



}
