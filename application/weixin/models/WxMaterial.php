<?php
namespace weixin\models;
use common\models\WxMaterial as Common;
use ext\weixin\mp\message\News;

class WxMaterial extends Common{
    public static function getNewsReply($result){
        $info = WxMaterial::find()
            ->where(['media_id'=>$result['media_id']])
            ->select("msg_type,content")
            ->one();
        if(empty($info)){
            return '查询不到信息';//msg_type
        }elseif($info['msg_type']!=2){
            return '查询不到信息';
        }
        $data = json_decode($info['content'],true);
        return new News(['props' =>[
            'ArticleCount'  => 1,
            'Articles'      => [
                'item'          => [
                    'Title'         => $data['title'],
                    'Description'   => $data['digest'],
                    'PicUrl'        => $data['thumb_url'],
                    'Url'           => $data['url'],
                    ]
            ]
        ]]);
    }





}
