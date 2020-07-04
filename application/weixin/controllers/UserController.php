<?php
namespace weixin\controllers;
use common\models\Delivery;
use ext\weixin\Application;
use weixin\models\Discount;
use weixin\models\User;
use weixin\models\WxUnionid;
use weixin\models\WxUser;
use weixin\models\WxUserTemp;
use weixin\models\WxUserTuiLog;
use Yii;
use yii\helpers\Url;


/**
 * Router controller
 */
class UserController extends BaseController
{
    //您没有执行此操作的权限
   /* public function behaviors()
    {

    }*/
    public function actionIndex(){
        /*if(empty($this->uid)){
            return $this->redirect(['getpacket']);
        }
        $model = new WxUserTuiLog();
        return $this->render('index',['model'=>$model]);*/
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/4/2 13:12
     */
    public function actionQrcode(){
        $key    = 'cache_signup_'.$this->uid;
        $pic_url = Yii::$app->cache->get($key);
        if(empty($pic_url)){
            $app    = new Application();
            $weixin = $app->driver("mp.qrcode");
            $str    = 'signup_'.$this->uid;
            $weixin->strTemp($str);
            $pic_url= $weixin->getQrcode();
            Yii::$app->cache->set($key,$pic_url,2000000);
        }

        $source_img = YII_DIR . '/templets/themes/weixin/assets/images/mp_make_qrcode.jpg';
        $wimg_x  = 180; //水印x坐标
        $wimg_y  = -150;
        $img = imagecreatefromjpeg($source_img); //读取原图
        $img_x = imagesx($img); //原图宽
        $img_y = imagesy($img); //原图高

        imagealphablending($img, true);//设置为混合填色模式
        //$img_water_map = imagecreatefromjpeg($water_map); //读取水印图片
        $img_water_map = imagecreatefromjpeg($pic_url);
        $water_x = imagesx($img_water_map); //水印宽
        $water_y = imagesy($img_water_map); //水印高
        if($wimg_x<0){
            $wimg_x = $img_x - $water_x + $wimg_x; //水印x坐标
        }
        if($wimg_y<0){
            $wimg_y = $img_y - $water_y + $wimg_y; //水印y坐标
        }
        //imagecopy($img, $img_water_map, $wimg_x, $wimg_y, 0, 0, $water_x, $water_y); //分别为原图，水印，水印x坐标，水印y坐标，水印图片横轴开始点，水印图片纵轴开始点，水印横轴结束，水印纵轴结束
        imagecopy($img, $img_water_map, $wimg_x, $wimg_y, 0, 0, $water_x, $water_y); //分别为原图，水印，水印x坐标，水印y坐标，水印图片横轴开始点，水印图片纵轴开始点，水印横轴结束，水印纵轴结束
        //header('Content-Type: image/jpeg');
        //imagejpeg($img,YII_DIR.'/uploads/qrcode/u_'.$this->uid.'.jpg',50);
        //$a = imagejpeg($img,null,30);
        ob_start();
        imagejpeg($img, null, 85);
        $data = ob_get_clean();
        $pic_url = "data:image/png;base64,".base64_encode($data);
        imagedestroy($img); //销毁内存数据流
        imagedestroy($img_water_map);
        return $this->render('qrcode',['pic_url'=>$pic_url]);
        //exit;
    }
    public function actionMiniQrcode(){
        if(empty($this->wx_temp_id)){
            header('Content-Type: image/jpeg');
            $pic_url =  YII_DIR.'/templets/themes/weixin/assets/images/mp.jpg';
            $img = imagecreatefromjpeg($pic_url);
            imagejpeg($img);
            imagedestroy($img);
            exit;
        }
        $app    = new Application();
        $weixin = $app->driver("mini.qrcode");
        $str    = 'act=binding&id='.$this->wx_temp_id;
        $extra  = ['is_hyaline'=>true];
        $base64 =  $weixin->unLimit($str,'pages/index/index',$extra);

        $water_map =  $base64;//YII_DIR . '/uploads/mini/86fa4c9f2c966e1f8ec1013f4afdde2b.png';
        $source_img = YII_DIR . '/templets/themes/weixin/assets/images/mini_make_qrcode.jpg';
        $wimg_x  = 180; //水印x坐标
        $wimg_y  = -150;
        $img = imagecreatefromjpeg($source_img); //读取原图
        $img_x = imagesx($img); //原图宽
        $img_y = imagesy($img); //原图高

        imagealphablending($img, true);//设置为混合填色模式
        //$img_water_map = imagecreatefromjpeg($water_map); //读取水印图片
        $img_water_map = imagecreatefromstring($water_map);
        $water_x = imagesx($img_water_map); //水印宽
        $water_y = imagesy($img_water_map); //水印高
        if($wimg_x<0){
            $wimg_x = $img_x - $water_x + $wimg_x; //水印x坐标
        }
        if($wimg_y<0){
            $wimg_y = $img_y - $water_y + $wimg_y; //水印y坐标
        }
        imagecopy($img, $img_water_map, $wimg_x, $wimg_y, 0, 0, $water_x, $water_y); //分别为原图，水印，水印x坐标，水印y坐标，水印图片横轴开始点，水印图片纵轴开始点，水印横轴结束，水印纵轴结束
        header('Content-Type: image/jpeg');
        imagejpeg($img);
        //imagejpeg($img, $source_img, 95); //输出到目标文件
        imagedestroy($img); //销毁内存数据流
        imagedestroy($img_water_map);
        exit;
    }
    /**
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/28 15:30
     */
    public function actionGetpacket(){



        return $this->render('getpacket');
    }
    /**
     * 用户分享
     */
    public function actionShare(){
        $key    = 'cache_signup_'.$this->uid;
        $pic_url = Yii::$app->cache->get($key);
        if(empty($pic_url)){
            $app    = new Application();
            $weixin = $app->driver("mp.qrcode");
            $str    = 'signup_'.$this->uid;
            $weixin->strTemp($str);
            $pic_url= $weixin->getQrcode();
            Yii::$app->cache->set($key,$pic_url,2000000);
        }
        $source_img = YII_DIR . '/templets/themes/weixin/assets/images/mp_make_qrcode.jpg';
        $wimg_x  = 180; //水印x坐标
        $wimg_y  = -150;
        $img = imagecreatefromjpeg($source_img); //读取原图
        $img_x = imagesx($img); //原图宽
        $img_y = imagesy($img); //原图高
        imagealphablending($img, true);//设置为混合填色模式
        $img_water_map = imagecreatefromjpeg($pic_url);
        $water_x = imagesx($img_water_map); //水印宽
        $water_y = imagesy($img_water_map); //水印高
        if($wimg_x<0){
            $wimg_x = $img_x - $water_x + $wimg_x; //水印x坐标
        }
        if($wimg_y<0){
            $wimg_y = $img_y - $water_y + $wimg_y; //水印y坐标
        }
        imagecopy($img, $img_water_map, $wimg_x, $wimg_y, 0, 0, $water_x, $water_y); //分别为原图，水印，水印x坐标，水印y坐标，水印图片横轴开始点，水印图片纵轴开始点，水印横轴结束，水印纵轴结束
        ob_start();
        imagejpeg($img, null, 75);
        $data = ob_get_clean();
        $pic_url = "data:image/png;base64,".base64_encode($data);
        imagedestroy($img); //销毁内存数据流
        imagedestroy($img_water_map);
        return $this->render('share',['pic_url'=>$pic_url]);
    }
    /**
     *
     */
    public function actionMessage($id){
        //return $this->render('message',['model'=>['status'=>false,'message'=>'发送失败','info'=>'没有权限发送']]);
        if(empty($this->uid)){
            //$this->error('没有权限发送');
            return $this->render('message',['model'=>['status'=>false,'message'=>'发送失败','info'=>'没有权限发送']]);
        }
        $model = new Delivery();
        $model   = $model::SendWarn($id,$this->uid);
        return $this->render('message',['model'=>$model]);
        /*if($ret['status'] == true){

        }else{

        }*/
    }
    /**
     * 绑定 binding
     */
    public function actionBinding(){
        $token = Yii::$app->request->get('token','');
        $t_uid = Yii::$app->request->get('t_uid',0);//print_r($t_uid);exit;
        $user  = User::find()
            ->where("password_reset_token=:password_reset_token")
            ->addParams([':password_reset_token'=>$token])
            ->select("id")
            ->asArray()
            ->one();
        if(empty($user)){
            return $this->render('binding',['model'=>['status'=>false,'message'=>'参数出错']]);
        }
        $uid = $user['id'];
        $id  = $this->getUserInfo['id'];
        $ret = WxUser::updateAll(['uid'=>$uid],'id=:id',[':id'=>$id]);
        if(!empty($ret)){
            WxUnionid::updateAll(['mp_openid'=>$this->openid],'uid=:uid',[':uid'=>$uid]);
            User::updateAll(['password_reset_token'=>null],'id=:id',[':id'=>$uid]);
            //写入优惠券
            $discount_id = 1;
            if($t_uid == 3){
                $discount_id = 3;
            }
            $result = Discount::getDiscount($discount_id,$uid,0,true);
            if($result['status'] == true){
                //推荐积分
                if(!empty($t_uid)){
                    if($t_uid != 3){
                        //查询关注信息
                        $userinfo = $this->getUserInfo;
                        $data = [
                            'uid'       =>$t_uid,
                            'openid'    =>$userinfo['openid'],
                            'nickname'  =>$userinfo['nickname'],
                            'headimgurl'=>$userinfo['headimgurl'],
                        ];
                        WxUserTuiLog::wxUserJifen($data); //不提示 绑定用户才提示
                    }
                }
            }
        }
        return $this->render('binding',['model'=>['status'=>true,'message'=>'绑定成功']]);
    }
} 
