<?php

namespace weixin\services;
use weixin\models\InviterUser;
use Yii;
class Event extends Common {
    //private $eventType = ['subscribe','unsubscribe','SCAN','LOCATION','CLICK','VIEW'];
    //接收事件消息
    protected function init(){
        $event   = $this->message['Event']; //事件类型
        switch ($event)
        {
            case 'subscribe':
                $this->receiveSubscribe();
            break;
            case 'unsubscribe':
                $this->receiveUnsubscribe();
            break;
            case 'SCAN':
                $this->receiveScan();
            break;
            case 'LOCATION':
                $this->receiveLocation();
            break;
            case 'CLICK':
                $this->receiveClick();
            break;
            case 'VIEW':
                $this->receiveView();
                break;
            default:
                $this->getResult =  '消息类型不支持';
                return true;
        }
        return true;
    }

    /**
     * 关注类型
     */
    private function receiveSubscribe(){
        $this->getResult = \weixin\behavior\event\Subscribe::run($this->message);
    }

    /**
     * 取消关注
     */
    private function receiveUnsubscribe(){
        \weixin\behavior\event\Unsubscribe::run($this->message);
    }
    /**
     * 用户已关注时的事件推送
     */
    private function receiveScan(){
        return \weixin\behavior\event\Scan::run($this->message);


        //$this->getResult = WxReply::getReplyType(2);
    }
    /**
     * 上报地理位置事件
     */
    private function receiveLocation(){

    }
    /**
     * 点击菜单拉取消息时的事件推送
     */
    private function receiveClick(){

        switch ($this->message['EventKey'])
        {
            case 'yaoqing':
                $pic_url = InviterUser::pushQRcode($this->message['FromUserName']);
                $this->getResult =$pic_url;
            break;
        }
    }
    /**
     * 点击菜单拉取消息时的事件推送
     */
    private function receiveView(){

    }
}