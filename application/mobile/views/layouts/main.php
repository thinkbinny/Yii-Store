<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use assets\mobileAsset;
mobileAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="page-group">
    <div class="page">
        <?= $content ?>
    </div>
</div>
<?php
    if(isset($this->params['content'])){
        echo $this->params['content'];
    }
$this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>



