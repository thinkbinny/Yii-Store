<?php
namespace backend\controllers;

use backend\components\NotFoundHttpException;
use common\components\Func;
use Yii;
use yii\rbac\Item;
use backend\models\Menu;
use backend\models\AuthRule;
use backend\models\AuthItem;
use backend\models\search\AuthItemSearch;


class RoleController extends BaseController {

    public $type = Item::TYPE_ROLE;

    public function actionIndex() {
        $searchModel = new AuthItemSearch();
        $searchModel->type = $this->type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //var_dump($dataProvider);exit();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new AuthItem(null);
        $model->type = $this->type;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        if( $id == 'administors'){
            $this->error('禁止删除 Administors');
        }
        Yii::$app->authManager->remove($model->item);
        Yii::$app->authManager->removeChildren($model->item);
        return $this->redirect(['index']);
    }

    public function actionAuth($id) {
        $this->layout = '_main_ajax';
        $authManager = Yii::$app->authManager;
        if(Yii::$app->request->isPost) {
            $rules = Yii::$app->request->post('rules', []);
            if(!$role = $authManager->getRole($id)) {
                $this->error('角色不存在');
                //Yii::$app->session->setFlash('error', '角色不存在');
            }
            //删除角色所有child
            $authManager->removeChildren($role);
            foreach ($rules as $rule) {
                if(!empty($rule)){
                    //auth_rule表
                    $ruleModel = new AuthRule();
                    $ruleModel->name = $rule;
                    $ruleModel->save();
                    //auth_item表
                    $itemModel = new AuthItem($authManager->getPermission($rule));
                    $itemModel->name = $rule;
                    $itemModel->type = Item::TYPE_PERMISSION;
                    $itemModel->ruleName = $rule;
                    $itemModel->save();
                    //auth_item_child表
                    if(!$authManager->hasChild($role, $itemModel)) {
                       $authManager->addChild($role, $itemModel);
                    }
                }
            }
            $this->success('操作成功');
            //Yii::$app->session->setFlash('success', '操作成功');
        }
        $MenuList  = Menu::find()->asArray()->all();
        $node_list = Func::list_to_tree($MenuList,$pk='id',$pid='pid',$child='child',$root=0);
        $authRules = $authManager->getChildren($id);
        $authRules = array_keys($authRules);
        return $this->render('auth', [
            'node_list'       => $node_list,
            'authRules'     => $authRules,
            'role'          => $id,
        ]);
    }

    protected function findModel($id) {
        $authManager = Yii::$app->authManager;
        $item = $this->type === Item::TYPE_ROLE ? $authManager->getRole($id) : $authManager->getPermission($id);
        if($item) return new AuthItem($item);
        else throw new NotFoundHttpException("The requested page does not exist.");
    }

}