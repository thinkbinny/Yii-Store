<?php
namespace backend\models;

use Yii;

use common\models\Logistics as common;
use yii\helpers\ArrayHelper;



class Logistics extends common
{
    const CACHE_KEY_CODE_DATA = 'cache_key_Logistics_code_data_list';
    const STATUS_ACTIVE           = 1;
    const STATUS_DELETED          = 0;

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 15:25
     */
    public function rules()
    {
        return [
            [['name','code','sort'], 'trim'],
            [['name','code','sort'], 'required'],
            ['code', 'unique'],
            [['id','sort','status','created_at','updated_at'], 'integer'],
            [['name','code'], 'string', 'max' => 50],
            [['sort'], 'default', 'value' =>50],
            [['status'], 'default', 'value' =>1],
            ['code','verifyCode']
        ];
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 15:28
     */
    public function verifyCode($attribute){
        $data = self::getCodeData();
        if(!isset($data[$this->$attribute])){
            $this->addError($attribute,'物流代码不存在，请对应编码表');
        }
    }


    /**
     * @var array
     */
    public static $status = [
        self::STATUS_ACTIVE => '启用',
        self::STATUS_DELETED => '禁止',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/17 11:58
     */
    public function getStatus(){
        return self::$status;
    }

    /**
     * @return mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/17 11:58
     */
    public function getStatusText(){
        if(isset(self::$status[$this->status])){
            return self::$status[$this->status];
        }else{
            return '--';
        }
    }

    /**
     * @return array|mixed|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/4 14:11
     */
    public static function getFindDataList(){
        $key  = self::CACHE_KEY_LIST;
        $data = Yii::$app->cache->get($key);
        if($data === false){
        $data = self::find()
            ->where("status=:status")
            ->addParams([':status'=>1])
            ->select("code,name")
            ->asArray()
            ->orderBy(['sort'=>SORT_ASC,'updated_at'=>SORT_DESC])
            ->all();
        $data = ArrayHelper::map($data,'code','name');
        Yii::$app->cache->set($key,$data);
        }
        if(empty($data)){
            return [];
        }
        return $data;
    }

    /**
     *
     */
    public static function getCodeData(){
        $key = self::CACHE_KEY_CODE_DATA;
        $data = Yii::$app->cache->get($key);
        if($data !== false){
            return $data;
        }
        $allPath =  YII_DIR.Yii::$app->params['assetsUrl'].'/excel/100.xlsx';
        $PHPReader = new \extensions\excel\PHPExcel\Reader\Excel2007();
        if (!$PHPReader->canRead($allPath)) {
            $PHPReader = new \extensions\excel\PHPExcel\Reader\Excel5();
            if (!$PHPReader->canRead($allPath)) {
                return [];
            }
        }
        $phpExcel = $PHPReader->load($allPath);
        $currentSheet = $phpExcel->getSheet(0);  //读取excel文件中的第一个工作表
        $allColumn = $currentSheet->getHighestColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $excelResult = array();  //声明数组


        //从第一行开始读取数据
        $startRow =  3;
        $endRow = $startRow + 500;
        if($endRow >= $allRow){
            $endRow = $allRow;
        }
        for ($j = $startRow; $j <= $endRow; $j++) {
            //从A列读取数据
            for ($k = 'A'; $k <= $allColumn; $k++) {
                // 读取单元格
                $excelResult[$j][] = (string)$phpExcel->getActiveSheet()->getCell("$k$j")->getValue();
            }
        }
        if(empty($excelResult)){
            return [];
        }
        $data = ArrayHelper::index($excelResult,1);
        Yii::$app->cache->set($key,$data,86400);
        return $data;
    }


}
