<?php
namespace weixin\models;
use common\models\WxReply as Common;
use ext\weixin\mp\message\Image;
use ext\weixin\mp\message\Video;
class WxReply extends Common{

    public static function getMediaIdReply($result){
        if($result['msg_type']==2){//图文
            return WxMaterial::getNewsReply($result);
        }elseif($result['msg_type']==3){ //图片
            return new Image(['props' =>[
                'Image'=>[
                    'MediaId'=>$result['media_id']
                ]
            ]]);
        }elseif($result['msg_type']==4){ //视频
            return new Video(['props' =>[
                'Video'=>[
                    'MediaId'=>$result['media_id']
                ]
                ]]);//return ['MsgType'=>'video','MediaId'=>$result['media_id']];
        }else{
            return '目前不支持类型';
        }
    }

    /**
     * @param $result
     * @return bool|string
     */
    public static function getReplyText($result){
        if(!empty($result)){
            if($result['msg_type']==1){
                return $result['content'];
            }else{
                return self::getMediaIdReply($result);//$result['msg_type'].'目前不支持图文';//$result['media_id'];
            }
        }else{
            return false;
        }
    }

    /**
     * @param int $type
     * @return bool|string
     */
    public static function getReplyType($type=0){
        $model = new WxReply();
        $result    = $model->find()
            ->where(['type'=>$type])
            ->select("msg_type,content,media_id")
            ->asArray()
            ->one();

        return self::getReplyText($result);
    }

    /**
     * @param $keyword
     * @return bool|string
     */
    public static function reply($keyword){
        $reply_id = WxReplyKey::getReplyId($keyword);
        $model = new WxReply();
        if(!empty($reply_id)){
            $result   = $model->find()
                ->where("id=:id")
                ->addParams([':id'=>$reply_id])
                ->select("msg_type,content,media_id")
                ->asArray()
                ->one();
            return self::getReplyText($result);
        }
        return self::getReplyType();
    }
    /**
     *
     */

}
