<?php
namespace backend\models;
use Yii;
use extensions\weixin\Application;
use common\models\WxMaterial as Common;

/**
 * This is the model class for table "{{%wx_reply}}".

 */
class WxMaterial extends Common
{

    /** scenarios
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','msg_type','media_id'], 'required'],
            ['media_id', 'unique'],
            [['msg_type','created_at','updated_at'], 'integer'],
            [['media_id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 32],
            [['media_id'], 'string', 'max' => 64],
            ['content', 'safe'],
            [['created_at','updated_at'], 'default', 'value' =>time()],
        ];
    }

    /**
     * 获取数据
     * @param string $msg_type
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    protected function getMaterial($msg_type='news',$offset=0,$limit = 20){
        //$conf = ['app_id'=>'wxf74cbee6a7910036','secret'=>'48c89431f225a784388325abb76c6d0d','token'=>'wxtk2018jin'];
        //['conf'=>$conf]
        $app = new Application();
        $weixin = $app->driver("mp.resource");
        $result = $weixin->foreverMediaList($msg_type,$offset,$limit); //'news' 'video' image
        return $result;
    }

    /**
     * @param $result
     */
    protected function saveNews($result){
        foreach ($result['item'] as $val){
            $model = Common::findOne(['media_id'=>$val['media_id']]);
            if(empty($model)){
                $model = new Common;
            }
            $model->media_id    = $val['media_id'];
            $model->msg_type    = 2;
            $content            = current($val['content']['news_item']);
            $model->name        = $content['title'];
            $model->content     = json_encode($content);
            $model->created_at  = $val['content']['create_time'];
            $model->updated_at  = $val['content']['update_time'];

            if($model->validate()){
                $model->save();
            }
        }
    }
    protected function saveImage($result){
        foreach ($result['item'] as $val){
            $model = Common::find()->where(['media_id'=>$val['media_id']])->one();
            if(empty($model)){
                $model = new Common;
                $model->created_at  = $val['update_time'];
            }
            $model->msg_type    = 3;
            $model->media_id    = $val['media_id'];
            $model->name        = $val['name'];
            $model->content     = $val['url'];
            $model->updated_at  = $val['update_time'];
            if($model->validate()){
                $model->save();
            }
        }
    }
    protected function saveVideo($result){
        foreach ($result['item'] as $val){
            $model = Common::find()->where(['media_id'=>$val['media_id']])->one();
            if(empty($model)){
                $model = new Common;
                $model->created_at  = $val['update_time'];
            }
            $model->msg_type    = 4;
            $model->media_id    = $val['media_id'];
            $model->name        = $val['name'];
            $model->content     = '';
            $model->updated_at  = $val['update_time'];
            if($model->validate()){
                $model->save();
            }
        }
    }
    /**
     * @param $$msg_type
     * @param int $offset
     * @param int $limit
     */
    protected function saveData($msg_type,$offset=0,$limit = 20){
        $result = $this->getMaterial($msg_type,$offset,$limit);
        if(empty($result['total_count'])){
            return ;
        }
        if($msg_type=='news'){
            $this->saveNews($result);
        }elseif($msg_type=='image'){
            $this->saveImage($result);
        }elseif($msg_type=='video'){
            $this->saveVideo($result);
        }
        //如果还有值 在加载一次
        $total_count = $offset+$limit;
        if($result['item_count']>$total_count){
            $this->SaveData($msg_type,$total_count);
        }
    }
    /**
     *
     */
    public function caijisave(){
        $msg_type = 'news';
        switch ($this->msg_type)
        {
            case 1:
                $msg_type = 'text'; //图文
                break;
            case 2:
                $msg_type = 'news'; //图文
            break;
            case 3:
                $msg_type = 'image'; //图片
            break;
            case 4:
                $msg_type = 'video'; //视频
            break;
            case 5:
                $msg_type = 'voice'; //语音
            break;
        }

        $result = $this->saveData($msg_type);
        return true;

    }



}
