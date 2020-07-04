<?php
namespace backend\models;
use Yii;
use yii\behaviors\TimestampBehavior;
use extensions\weixin\Application;
use common\models\WxMiniQrcode as common;




class WxMiniQrcode extends common
{
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scene'], 'required'],
            ['scene', 'unique'],
            [['width','created_at','updated_at'], 'integer'],
            [['page','line_color'], 'string', 'max' => 50],
            [['scene'], 'string', 'max' => 32],
            [['pic_url'], 'safe'],
            [['auto_color','is_hyaline'],  'in', 'range' => [true,false]],
            [['width'], 'default', 'value' =>430],
            [['pic_url'], 'default', 'value' =>''],
            [['width'],'widthVerification'],
            [['line_color'], 'lineColorVerification'],
            ['scene','sceneVerification']
        ];
    }
    /**
     *
     */
    public function sceneVerification($attribute){
        if(empty(Yii::$app->params['weixin']['mini']['app_id']) || empty(Yii::$app->params['weixin']['mini']['secret'])){
            $this->addError($attribute,'小程序尚未配置');
        }else{
            /**
             * 生成二维码
             */
            if(empty($this->pic_url)){
                $app    = new Application();
                $weixin = $app -> driver('mini.qrcode');
                $extra  = [
                    'width'       => $this->width,
                    //'auto_color'  => $this->auto_color,
                    //'is_hyaline'  => $this->is_hyaline,
                ];

                if($this->auto_color == 'true'){
                    $extra['auto_color'] = true;
                }else{
                    $extra['auto_color'] = false;
                }
                if($this->is_hyaline == 'true'){
                    $extra['is_hyaline'] = true;
                }else{
                    $extra['is_hyaline'] = false;
                }

                if(!empty($this->line_color)){
                    $extra['line_color'] = json_decode($this->line_color,true);
                }

                 $data =  $weixin -> unLimit($this->scene,$this->page,$extra);//print_r(json_decode($data,true));
                 $ret = json_decode($data,true);
                 if(empty($ret)){
                     $pic_url = '/uploads/mini/';

                     if(!is_dir(YII_DIR.$pic_url)){
                         @mkdir (YII_DIR.$pic_url,0777,true);
                     }
                     $pic_url = $pic_url .uniqid(date('i')).'.png';
                     file_put_contents(YII_DIR.$pic_url, $data);
                     $this->pic_url =  $pic_url;
                 }else{
                     $this->addError($attribute,'生成小程序码：出错代码【'.$ret['errcode'].'】 '.$ret['errmsg']);
                     return false;
                 }/**/
                //$this->addError($attribute,'生成小程序码');
            }
        }
    }

    /**
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/9/7 20:09
     */
    public function widthVerification($attribute){
        if(!empty($this->{$attribute})){
            if($this->{$attribute}< 280){
                $this->addError($attribute,'宽度最小 280 px');
            }elseif($this->{$attribute}> 1280){
                $this->addError($attribute,'宽度最大 1280 px');
            }
        }
    }
    /**
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date DateTime
     */
    public function lineColorVerification($attribute){
        if(!empty($this->{$attribute})){
            if(is_null(json_decode($this->{$attribute}))){
                $data = explode(',',$this->{$attribute});
                $line_color = [
                  'r' =>  trim($data[0]),
                  'g' =>  trim($data[1]),
                  'b' =>  trim($data[2]),
                ];

                $this->{$attribute} = json_encode($line_color);
            }
        }
    }
}
