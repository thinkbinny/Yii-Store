<?php

namespace backend\components;
use Yii;
/**
 * NotFoundHttpException represents a "Not Found" HTTP exception with status code 404.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NotFoundHttpException extends \yii\web\HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {

        if(Yii::$app->request->isAjax){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['status'=>false,'message'=>$message]));
        }else{
            parent::__construct(404, $message, $code, $previous);
        }
    }
}
