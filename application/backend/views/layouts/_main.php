<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
use yii\helpers\Url;
use assets\backendAsset as AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
$this->registerCssFile(Yii::$app->params['assetsUrl'].'/css/style.css',[ 'depends' => 'assets\backendAsset']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body >
<?php $this->beginBody() ?>
<?= $content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
