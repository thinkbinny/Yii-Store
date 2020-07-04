<?php

namespace backend\models;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%model}}".
 */
class ModelField extends \yii\db\ActiveRecord
{

    public function __construct() {
        $this->isrequired = 1;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%model_field}}';
    }

    /**
     * @inheritdoc [['password'], 'required', 'on' => ['create']],
     */
    public function rules()
    {
        return [
            [['field','name','model_id','isrequired'], 'required'],//'model_id',
            ['formtype', 'required','message'=>"请选择{attribute}"],
            [['model_id','isrequired','minlength','maxlength','status','created_at','updated_at'], 'integer'],
            [['field', 'name','css','formtype'], 'string', 'max' => 30],
            [['pattern','errortips'], 'string', 'max' => 255],
            [['tips','setting'], 'string'],

            ['status', 'default', 'value' => 1],
            ['minlength', 'default', 'value' => 0],
            ['maxlength', 'default', 'value' => 0],
            ['field','match','pattern'=>'/^[a-z0-9\-_]+$/','message'=>'数据表名必须是英文或数字'],
            ['field','UniqueField','on' => ['create']],
            //['name','checkZName'],
            /*['name','unique','message'=>'数据表名已存在，请换一个'],*/
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '编号',
            'model_id'          => '模型ID',
            'field'             => '字段名',
            'name'              => '字段别名',
            'isrequired'        => '必填',//是否必填1是0否 数据校验正则
            'tips'              => '提示',
            'css'               => '样式',
            'minlength'         => '最小长度',
            'maxlength'         => '最大长度',
            'pattern'           => '校验正则',
            'errortips'         => '出错提示',
            'formtype'          => '字段类型',
            'setting'           => '设置',
            'status'            => '状态',// 状态1正常0禁止
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
            'sort'              => '顺序',
            'indexes'           => '索引',

        ];
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }
    public function afterValidate()
    {
        $setting                = Yii::$app->request->post('setting');
        $setting                = serialize($setting); //unserialize
        $this->setting          = $setting;
        $this->indexes          = 0;
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = [
            'model_id',
            'indexes',
            'field',
            'name',
            'isrequired',
            'tips',
            'css',
            'minlength',
            'maxlength',
            'pattern',
            'errortips',
            'formtype',
            'setting',
            'sort',
            'status',
            'created_at',
            'updated_at',
        ];
        $scenarios['update'] = [
            'model_id',
            'indexes',
            'field',
            'name',
            'isrequired',
            'tips',
            'css',
            'minlength',
            'maxlength',
            'pattern',
            'errortips',
            'formtype',
            'setting',
            'sort',
            'status',
            'created_at',
            'updated_at',
        ];
        $scenarios['sort'] = ['sort'];
        return $scenarios;
    }
    /**
     * 同一个表只能有一个字段
     */
    public function UniqueField(){
        $info = $this->find()
            ->where('field=:field AND model_id=:model_id')
            ->addParams([':field'=>$this->field,':model_id'=>$this->model_id])
            ->count();
        if(!empty($info)){
            $this->addError('name','字段别名已存在');
            return false;
        }
        return true;
    }


    //字段类型
    public function getFormtype(){
        return [
          'catid'       => '栏目',
          'title'       => '标题',
          'keyword'     => '关键词',
          'copyfrom'    => '来源',
          'islink'      => '转向链接',
          'text'        => '单行文本',
          'textarea'    => '多行文本',
          'editor'      => '编辑器',
          'box'         => '选项',
          'image'       => '图片',
          'number'      => '数字',
          'datetime'    => '日期和时间',
          //'linkage'     => '联动菜单',
          //'map'         => '地图字段',
          'omnipotent'  => '万能字段',
        ];

    }
    /*

*
*/
    public function getIsrequired(){
        return [
          1=>'是',
          0=>'否',
        ];
    }
    /* 操作的表名 */
    protected $table_name = null;
    /**
     * 过滤字段
     */
    protected function FilterField($model){
        $setting = unserialize($model->setting);

        switch ($model->formtype){
            case 'catid':
                $field = 'int(11) NOT NULL';
                $defaultvalue = 0;
                break;
            case 'text':
                $field = 'varchar(100) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
               break;
            case 'title':
                $field = 'varchar(120) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'keyword':
                $field = 'varchar(200) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'copyfrom':
                $field = 'varchar(200) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'islink':
                $field = 'tinyint(3) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'textarea':
                $field = 'varchar(255) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
               break;
            case 'editor':
                $field = 'text NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'box':
                if($setting['fieldtype']=='varchar'){
                    $field = 'varchar(100) NOT NULL';
                }elseif($setting['fieldtype']=='tinyint'){
                    $field = 'tinyint(3) NOT NULL';
                }elseif($setting['fieldtype']=='smallint'){
                    $field = 'smallint(5) NOT NULL';
                }elseif($setting['fieldtype']=='mediumint'){
                    $field = 'smallint(8) NOT NULL';
                }else{
                    $field = 'int(11) NOT NULL';
                }
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'image':
                $field = 'varchar(255) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'number':
                $field = 'int(11) NOT NULL';
                $defaultvalue = $setting['defaultvalue'];
                break;
            case 'datetime':
                $field = 'int(11) NOT NULL';
                $defaultvalue = 0;
                break;
            case 'omnipotent':
                if($setting['fieldtype']=='varchar'){
                    $field = 'varchar(100) NOT NULL';
                }elseif($setting['fieldtype']=='tinyint'){
                    $field = 'tinyint(3) NOT NULL';
                }elseif($setting['fieldtype']=='smallint'){
                    $field = 'smallint(5) NOT NULL';
                }elseif($setting['fieldtype']=='mediumint'){
                    $field = 'smallint(8) NOT NULL';
                }else{
                    $field = 'int(11) NOT NULL';
                }
                $defaultvalue = $setting['defaultvalue'];
                break;
              default:
                  $field = 'varchar(255) NOT NULL';
                  $defaultvalue = '';
        }

        return [
            'id'        =>  $model->id,
            'model_id'  =>  $model->model_id,
            'name'      =>  $model->field,
            'field'     =>  $field,
            'title'     =>  $model->name,
            'value'     =>  $defaultvalue,
        ];
    }
    /**
     * 检查当前表是否存在
     * @param intger $model_id 模型id
     * @return intger 是否存在
     * @author thinkbinny <274397981@qq.com>
     */
    protected function checkTableExist($model_id){
        $model = Model::find()
            ->where('id=:id')
            ->addParams([':id'=>$model_id])
            ->select('tablename')
            ->one();
        $table_name = Yii::$app->db->tablePrefix.strtolower($model->tablename);
        $this->table_name = $table_name;
        $sql = <<<sql
                SHOW TABLES LIKE '{$table_name}';
sql;
        $res = Yii::$app->db->createCommand($sql)->execute();
        return $res;
    }
    /**
     * 新建表字段
     * @param array $field 需要新建的字段属性
     * @return boolean true 成功 ， false 失败
     * @author thinkbinny <274397981@qq.com>
     */
    public function addField($field){
        $field = $this->FilterField($field);
        //检查表是否存在
        $table_exist = $this->checkTableExist($field['model_id']);
        //获取默认值
        if($field['value'] === ''){
            $default = '';
        }elseif (is_numeric($field['value'])){
            $default = ' DEFAULT '.$field['value'];
        }elseif (is_string($field['value'])){
            $default = ' DEFAULT \''.$field['value'].'\'';
        }else {
            $default = '';
        }
        $table_name = $this->table_name;
        if($table_exist){
            $sql = <<<sql
                ALTER TABLE `{$table_name}`
ADD COLUMN `{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}';
sql;
        }else{
            $model = Model::find()
                ->where('id=:id')
                ->addParams([':id'=>$field['model_id']])
                ->select('tablename,engine_type')
                ->one();
            //新建表时是否默认新增“id主键”字段
            $sql = <<<sql
                CREATE TABLE IF NOT EXISTS `{$table_name}` (
                `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自动ID' ,
                `{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}' ,
                PRIMARY KEY (`id`)
                )
                ENGINE={$model->engine_type}
                DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
                CHECKSUM=0
                ROW_FORMAT=DYNAMIC
                DELAY_KEY_WRITE=0
                ;
sql;

        }
        $res = Yii::$app->db->createCommand($sql)->execute();
        return $res !== false;
    }
    /**
     * 更新表字段
     * @param array $field 需要更新的字段属性
     * @return boolean true 成功 ， false 失败
     * @author thinkbinny <274397981@qq.com>
     */
    public function updateField($field){
        $field = $this->FilterField($field);//print_r($field);exit;
        //检查表是否存在
        $table_exist = $this->checkTableExist($field['model_id']);
        $table_name = $this->table_name;
        $default = $field['value'] != '' ? ' DEFAULT ' . $field['value'] : '';
        if($table_exist) {
            //获取原字段名
            $last = ModelField::find()
                ->where('id=:id')
                ->addParams([':id' => $field['id']])
                ->select('field')
                ->one();
            $last_field = $last->field;
            //获取默认值

            $sql = <<<sql
            ALTER TABLE `{$table_name}`
CHANGE COLUMN `{$last_field}` `{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}' ;
sql;
        }else{
            $model = Model::find()
                ->where('id=:id')
                ->addParams([':id'=>$field['model_id']])
                ->select('tablename,engine_type')
                ->one();
            //新建表时是否默认新增“id主键”字段
            $sql = <<<sql
                CREATE TABLE IF NOT EXISTS `{$table_name}` (
                `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自动ID' ,
                `{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}' ,
                PRIMARY KEY (`id`)
                )
                ENGINE={$model->engine_type}
                DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
                CHECKSUM=0
                ROW_FORMAT=DYNAMIC
                DELAY_KEY_WRITE=0
                ;
sql;
        }
        $res = Yii::$app->db->createCommand($sql)->execute();
        return $res !== false;
    }

    /**
     * 删除一个字段
     * @param array $field 需要删除的字段属性
     * @return boolean true 成功 ， false 失败
     * @author thinkbinny <274397981@qq.com>
     */
    public function deleteField($field){
        //检查表是否存在
        $table_exist = $this->checkTableExist($field['model_id']);

        $sql = <<<sql
            ALTER TABLE `{$this->table_name}`
DROP COLUMN `{$field['name']}`;
sql;
        $res = Yii::$app->db->createCommand($sql)->execute();
        return $res !== false;
    }

    /*
     * 索引
     */

    public function IndexesField($field){
        $table_exist = $this->checkTableExist($field['model_id']);
        if($table_exist){
        if($field['indexes']==1){
            $sql = <<<sql
            ALTER TABLE `{$this->table_name}` ADD INDEX ( `{$field['field']}` );
sql;
        }else{
            $sql = <<<sql
            ALTER TABLE `{$this->table_name}` DROP INDEX {$field['field']};
sql;

        }
        $res = Yii::$app->db->createCommand($sql)->execute();
        return $res !== false;
        }else{
            return false;
        }
    }
    /**
     * 生成正则 rules attributeLabels
     * @return array
     */
    public function getRegular(){
        $model_id   =   Yii::$app->session->get('model_id');
        $rules              = array();
        $attributeLabels    = array();
        $field              = 'field,name,errortips,pattern,minlength,maxlength,setting,isrequired,formtype';
        $volist = $this->find()
            ->where('model_id=:model_id')
            ->addParams([':model_id'=>$model_id])
            ->select($field)
            ->asArray()->orderBy('id asc,sort asc')
            ->all();
        $number    = ['catid','box','number','islink'];
        $required       = array();
        $integer        = array();
        $text           = array();
        $title          = array();
        $keyword        = array();
        $textarea       = array();
        $datetime       = array();
        $image          = array();
        $editor         = array();
        $defaultvalue0  = array();
        $defaultvalue1  = array();
        $defaultvalue   = array();
        $pattern        = array();
        foreach ($volist as $val){
            $attributeLabels[$val['field']] = $val['name'];
            if($val['isrequired']==1){
                $required[] = $val['field'];
            }
            if(in_array($val['formtype'],$number)){
                $integer[]  = $val['field'];
            }
            if($val['formtype']=='text'){
                $text[]     = $val['field'];
            }
            if($val['formtype']=='title'){
                $title[]     = $val['field'];
            }
            if($val['formtype']=='keyword' || $val['formtype']=='copyfrom'){
                $keyword[]     = $val['field'];
            }
            if($val['formtype']=='textarea'){
                $textarea[]     = $val['field'];
            }
            if($val['formtype']=='image'){
                $image[]     = [$val['field'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024];//$val['field'];
            }
            if($val['formtype']=='datetime'){
                $datetime[]     = $val['field'];
            }
            if($val['formtype']=='editor'){
                $editor[]     = $val['field'];
            }// decimaldigits
            $setting = unserialize($val['setting']);
            if(isset($setting['defaultvalue'])){
                if($setting['defaultvalue']=='0'){
                    $defaultvalue0[] = $val['field'];

                }elseif($setting['defaultvalue']==1){
                    $defaultvalue1[] = $val['field'];
                }elseif(!empty($setting['defaultvalue'])){
                    $defaultvalue[]  = [$val['field'], 'default', 'value' => $setting['defaultvalue']];
                }
            }
            if(!empty($val['pattern'])){
                if(empty($val['errortips'])){
                    $pattern[] = [$val['field'],'match','pattern'=>$val['pattern']];
                }else{
                    $pattern[] = [$val['field'],'match','pattern'=>$val['pattern'],'message'=>$val['errortips']];
                }
            }

        }
        //required 必填
        if(!empty($required))    $rules[] = [$required, 'required'];
        if(!empty($integer))     $rules[] = [$integer, 'integer'];
        if(!empty($text))        $rules[] = [$text, 'string','max' => 100];
        if(!empty($title))       $rules[] = [$title, 'string','max' => 120];
        if(!empty($keyword))     $rules[] = [$keyword, 'string','max' => 200];
        if(!empty($textarea))    $rules[] = [$textarea, 'string','max' => 255];
        if(!empty($datetime))    $rules[] = [$datetime, 'getDateTime'];
        if(!empty($editor))      $rules[] = [$editor, 'string'];
        if(!empty($defaultvalue0))$rules[] = [$defaultvalue0, 'default', 'value' => 0];
        if(!empty($defaultvalue1))$rules[] = [$defaultvalue1, 'default', 'value' => 1];
        //数组合并 // $defaultvalue
        if(!empty($defaultvalue)) $rules = array_merge($rules,$defaultvalue);
        if(!empty($pattern)) $rules = array_merge($rules,$pattern);

        return ['rules'=>$rules,'attributeLabels'=>$attributeLabels];
    }

    /**
     * @param $string
     * @return array|false|string[]
     */
    public function parse_attr($string){
        $rules = array();
        $array = preg_split('/[;\r\n]+/', trim($string, ";\r\n"));
        if(strpos($string,'|')){
            foreach ($array as $array_key=>$val) {
                $value  =   array();
                $data = explode('|', $val);
                foreach ($data as $key=>$val){
                    if(strpos($val,':')){
                        $value2  =   array();
                        foreach ($array as $val2) {
                            list($k, $v) = explode(':', $val);
                            //$value2[$k]   = $v;
                            $value[$k] = $v;
                        }
                        //$value[] = $value2;
                    }else{
                        if(strpos($val,',')) {
                            $value[] = preg_split('/[,]+/', trim($val, "{}"));
                        }else{
                            $value[] = trim($val,"{}");
                        }
                    }
                }
                $rules[$array_key] =  $value;
            }

        }else{
            $rules  =   $array;
        }
        //print_r($rules);exit;

        return $rules;
    }
    public function CreateFile($string)
    {
        $model_id   =   Yii::$app->session->get('model_id');
        $model = Model::find()
            ->where('id=:id')
            ->addParams([':id'=>$model_id])
            ->select('tablename')
            ->asArray()
            ->one();


        $string     = var_export($string,true);
        $string     = 'return '.$string.';';
        $dir        =   __DIR__ . '\articleConfig' ;
        $filename   =   $dir . '\\'.$model['tablename'].'.php';
        $myfile = fopen($filename, "w") or die("Unable to open file!");
        $string = <<<File
<?php 
$string 
?>
File;

        fwrite($myfile, $string);
        return fclose($myfile);
    }
}
