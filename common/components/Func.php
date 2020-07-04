<?php
/**
 * 公共函数
 * @author  七秒记忆 <QQ:274397981>
 */
namespace common\components;

use common\models\Action;
use common\models\ActionLog;
use common\models\Config;
use common\models\Member;
use common\models\UploadFile;
use common\models\User;
use common\models\WxTplMsg;
use Yii;

class Func {
    /**
     * 异常函数
     * try {
            echo 1/$num;

         } catch (Exception $e){
             echo $e->getMessage();
         }
     */
    /**
     * 转换字节数为其他单位
     * @param string $filesize 字节大小
     * @return string 返回大小
     */
    public static function sizeCount($filesize){
        if ($filesize >= 1073741824) {
            $filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
        } elseif ($filesize >= 1048576) {
            $filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
        } elseif ($filesize >= 1024) {
            $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
        } else {
            $filesize = $filesize . ' Bytes';
        }
        return $filesize;
    }

    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     * @return array
     */
    public static function list_sort_by($list,$field, $sortby='asc') {
        if(is_array($list)){
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ( $refer as $key=> $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     * @author Thinkbinny <274397981@qq.com>
     */
    public static function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0){
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
    /**
     * 将list_to_tree的树还原成列表
     * @param  array $tree  原来的树
     * @param  string $child 孩子节点的键
     * @param  string $order 排序显示的键，一般是主键 升序排列
     * @param  array  $list  过渡用的中间数组，
     * @return array        返回排过序的列表数组
     * @author Thinkbinny <274397981@qq.com>
     */
    public  static  function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
        if(is_array($tree)) {
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if(isset($reffer[$child])){
                    unset($reffer[$child]);
                    Func::tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = Func::list_sort_by($list, $order, $sortby='asc');
        }
        return $list;
    }
    // 分析枚举类型配置值 格式 a:名称1,b:名称2
    public static function parse_config_attr($string) {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if(strpos($string,':')){
            $value  =   array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        }else{
            $value  =   $array;
        }
        return $value;
    }
    // 分析枚举类型字段值 格式 a:名称1,b:名称2
    // 暂时和 parse_config_attr功能相同
    // 但请不要互相使用，后期会调整
    public static function parse_field_attr($string) {
        if(0 === strpos($string,':')){
            // 采用函数定义
            return   eval('return '.substr($string,1).';');
        }elseif(0 === strpos($string,'[')){
            // 支持读取配置参数（必须是数组类型）
            return Yii::$app->params[substr($string,1,-1)];
        }

        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if(strpos($string,':')){
            $value  =   array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        }else{
            $value  =   $array;
        }
        return $value;
    }

    /**
     * 检查$pos(推荐位的值)是否包含指定推荐位$contain
     * @param number $pos 推荐位的值
     * @param number $contain 指定推荐位
     * @return boolean true 包含 ， false 不包含
     * @author huajie <banhuajie@163.com>
     */
    public static function check_document_position($pos = 0, $contain = 0){
        if(empty($pos) || empty($contain)){
            return false;
        }

        //将两个参数进行按位与运算，不为0则表示$contain属于$pos
        $res = $pos & $contain;
        if($res !== 0){
            return true;
        }else{
            return false;
        }
    }



    /**
     * @param array $input
     * @param null $columnKey
     * @param null $indexKey
     * @return array
     */
    public static function array_column(array $input, $columnKey=null, $indexKey = null){
        if(!function_exists('array_column')){
                $result = array();
                if (null === $indexKey) {
                    if (null === $columnKey) {
                        $result = array_values($input);
                    } else {
                        foreach ($input as $row) {
                            $result[] = $row[$columnKey];
                        }
                    }
                } else {
                    if (null === $columnKey) {
                        foreach ($input as $row) {
                            $result[$row[$indexKey]] = $row;
                        }
                    } else {
                        foreach ($input as $row) {
                            $result[$row[$indexKey]] = $row[$columnKey];
                        }
                    }
                }
                return $result;
        }else{

            return array_column($input,$columnKey,$indexKey);
        }
    }
    /**
     * 获取客户端IP地址 还原long2ip函数
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
    /**
     * 验证手机号码
     */
    //移动段 134,135,136,137,138,139, 147 ,150,151,152,157,158,159, 178, 182,183,184,187,188, 198
    //联通段 130,131,132, 145, 155,156, 166, 176, 185,186,
    //电信段 133, 153, 173,177, 180,181,189, 199
    //虚拟段 170,171
    /**
     * 13段 0-9;
     * 14段 57;
     * 15段 012356789;
     * 16段 6;
     * 17段 3678;
     * 18段 0-9;
     * 19段 89;
     */
    /*
     *  $data = [
            134,135,136,137,138,139,147,150,151,152,157,158,159,178,182,183,184,187,188,198, //移动段
            130,131,132,155,156,166,185,186,145,176, //联通段
            133,153,177,173,180,181,189,199 //电信段
        ];
    */
    public static function isMobile($mobile){
        if(strlen($mobile)!=11) return false;
        $preg = '/13[0-9]{1}\d{8}|14[57]{1}\d{8}|15[012356789]{1}\d{8}|166\d{8}|17[3678]{1}\d{8}|18[0-9]{1}\d{8}|19[89]{1}\d{8}/';
        if(preg_match($preg,$mobile)){
            return true; //验证通过
        }else{
            return false;//验证不通过
        }
    }
    /**
     * 时间戳格式化
     * @param int $time
     * @return string 完整的时间显示
     * @author huajie <banhuajie@163.com>
     */
    public static function time_format($time = NULL,$format='Y-m-d H:i'){
        $time = $time === NULL ? time() : intval($time);
        return date($format, $time);
    }
    /**
     * 根据后台用户ID获取用户名
     * @param  integer $uid 用户ID
     * @return string       用户名
     */
    public function get_admin_username($uid = 0){
        static $list;
        if(!($uid && is_numeric($uid))){ //获取当前登录用户名
            return Yii::$app->user->identity->username;
        }
        $K = Yii::$app->params['USER_ADMIN_KEY_CACHE'];
        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get($K);
        }
        /* 查找用户信息 */
        $key = "u{$uid}";
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else { //调用接口获取用户信息
            $User = new \backend\models\Admin();
            $info = $User->find()
                ->where("id=:id")
                ->addParams([':id'=>$uid])
                ->select('username')
                ->asArray()
                ->one();
            if($info && isset($info['username'])){
                $name = $list[$key] = $info['username'];
                /* 缓存用户 */
                $count = count($list);
                $max   = Yii::$app->params['USER_ADMIN_MAX_CACHE'];
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set($K,$list,9999999);
            } else {
                $name = '';
            }
        }
        return $name;
    }
    /**
     * 根据用户ID获取用户名
     * @param  integer $uid 用户ID
     * @return string       用户名
     */
    public static function getUsername($uid = 0){
        static $list;
        if(!($uid && is_numeric($uid))){ //获取当前登录用户名
            return Yii::$app->user->identity->username;
        }
        $k = Yii::$app->params['USER_KEY_CACHE'];
        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get($k);
        }
        /* 查找用户信息 */
        $key = "u{$uid}";
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else { //调用接口获取用户信息
            $info = User::find()
                ->where(['id'=>$uid])
                ->asArray()
                ->select("username")
                ->one();

            if($info && isset($info['username'])){
                $name = $list[$key] = $info['username'];
                /* 缓存用户 */
                $count = count($list);
                $max   = Yii::$app->params['USER_MAX_CACHE'];
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set($k,$list,999999);
            } else {
                $name = '';
            }
        }
        return $name;
    }

    /**
     * 根据用户ID获取用户昵称
     * @param  integer $uid 用户ID
     * @return string       用户昵称
     */
    public static function get_nickname($uid = 0){
        static $list;

        if(!($uid && is_numeric($uid))){ //获取当前登录用户名
            return Yii::$app->user->identity->username;
        }
        $k = Yii::$app->params['USER_NICKNAME_KEY_CACHE'];
        /* 获取缓存数据 */
        if(empty($list)){
            $list = Yii::$app->cache->get($k);
        }

        /* 查找用户信息 */
        $key = "u{$uid}";
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else { //调用接口获取用户信息
            $info = Member::find()
                ->where(['uid'=>$uid])
                ->select('nickname')
                ->asArray()
                ->one();
            if($info !== false && $info['nickname'] ){
                $nickname = $info['nickname'];
                $name = $list[$key] = $nickname;
                /* 缓存用户 */
                $count = count($list);
                $max   = Yii::$app->params['USER_NICKNAME_MAX_CACHE'];
                while ($count-- > $max) {
                    array_shift($list);
                }
                Yii::$app->cache->set($k,$list,999999);
            } else {
                $name = '';
            }
        }
        return $name;
    }

    /**
     * 根据用户ID获取用户昵称
     * @param  integer $uid 用户ID
     * @param  string $field 支持 nickname headimgurl
     * @return string       用户昵称
     */
    public static function getMemberInfo($uid = 0,$field = false){
        return Member::getMemberInfo($uid,$field);
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @return string
     */
    public static function  msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice.'...' : $slice;
    }



    /**
     * @param $price 转换成金钱
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/1 23:42
     */
    public static function priceFormat($price){
        //$price = floatval($price);
        $price = number_format($price, 2, '.', '');
        $price = sprintf("%1\$.2f",$price);
        return $price;
    }

    /**
     * @param array $array
     * @return null
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/1 23:43 获取第一个数组KEY值
     */
    public static function arrayKeyFirst(array $array){
        return $array ? array_keys($array)[0] : null;
    }

    /**
     * @param array $array
     * @return null
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/3/1 23:45 获取最后一个数组KEY值
     */
    public static function arrayKeyLast(array $array){
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
        return array_keys($array)[count($array)-1];
    }


    /**
     * 获取文档封面图片
     * @param int $image_id
     * @param string $field
     *  完整的数据  或者  指定的$field字段值
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/15 16:16
     */
    public static function getImageUrl($image_id){
        return UploadFile::getImageUrl($image_id);
    }

    /**
     * 创建CSV
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/13 14:29
     */
    public static function setCsv($fileName,$headlist,$data=array()){
        set_time_limit(1800);//制脚本执行时间为半个小时
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
        header('Cache-Control: max-age=0');
        //打开PHP文件句柄,php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        //输出Excel列名信息
        foreach ($headlist as $key => $value) {
            //CSV的Excel支持GBK编码，一定要转换，否则乱码
            $headlist[$key] = iconv('utf-8', 'gbk', $value);
        }

        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $headlist);

        //计数器
        $num = 0;

        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        //逐行取出数据，不浪费内存
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }
            $row = $data[$i];
            foreach ($row as $key => $value) {
                $row[$key] = "\t".iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
    }

    public static function getCsv($file){
        if (!is_file($file)) {
            return [
              'status'  => false,
              'message' => '没有文件',
            ];
        }
        $handle = fopen($file, 'r');
        if (!$handle) {
            return [
                'status'  => false,
                'message' => '读取文件失败',
            ];
        }
        $data = array();
        while (($row = fgetcsv($handle)) !== false) {
                   foreach ($row as $key => $value) {
                $row[$key] = iconv('gbk', 'utf-8', $value);
            }
            $data[] = $row;
        }

        fclose($handle);
        return [
            'status'=> true,
            'data'  => $data,
        ];

    }
}