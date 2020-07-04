<?php
/**
 * 控制过滤器, 集成了RBAC菜单权限验证
 * @author 边走边乔 <771405950>
 */
namespace mobile\components;

use Yii;
use yii\web\User;
use yii\di\Instance;
use yii\web\ForbiddenHttpException;

//class AccessControl extends \yii\base\ActionFilter {
class AccessControl extends \yii\filters\AccessControl {

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
   /* protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            if(Yii::$app->request->isAjax){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['status'=>false,'message'=>Yii::t('yii', 'You are not allowed to perform this action.')]));
            }else{
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
    }*/


}