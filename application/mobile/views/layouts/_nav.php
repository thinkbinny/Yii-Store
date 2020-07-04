<?php
use yii\helpers\Url;
?>

<nav class="bar bar-tab">
    <a class="tab-item active" href="<?=Url::to(['index/index'])?>">
        <span class="icon icon-home"></span>
        <span class="tab-label">首页</span>
    </a>

    <a class="tab-item" href="#">
        <span class="icon icon-app"></span>
        <span class="tab-label">分类</span>
    </a>
    <a class="tab-item" href="#">
        <span class="icon icon-cart"></span>
        <span class="tab-label">购物车</span>
    </a>
    <a class="tab-item" href="<?=Url::to(['user/index'])?>">
        <span class="icon icon-me"></span>
        <span class="tab-label">我</span>
    </a>
</nav>