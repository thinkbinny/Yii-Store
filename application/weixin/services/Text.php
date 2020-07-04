<?php

namespace weixin\services;
use weixin\models\WxReply;
use Yii;

class Text extends Common {


    //接收文本消息
    protected function init(){
        $message = $this->message;
        $keyword = trim($message['Content']);
        $this->getResult =  WxReply::reply($keyword);
        //$message = json_encode($this->getResult);
        //file_put_contents(YII_DIR.'/weixin2.log', $message."\r\n", FILE_APPEND);
    }

    /**
     * @param $cp
     * @return string 字节转Emoji表情
     *
     */
    private function bytes_to_emoji($cp)
    {
        if ($cp > 0x10000){       # 4 bytes
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x800){   # 3 bytes
            return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x80){    # 2 bytes
            return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else{                    # 1 byte
            return chr($cp);
        }
    }
}