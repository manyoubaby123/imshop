<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $model \im\cms\models\MenuItem */
/* @var $level int */
/* @var $linkOptions array */
/* @var $isActive bool */

$isActive = isset($isActive) ? $isActive : false;
$linkOptions = isset($linkOptions) ? array_merge_recursive($linkOptions, $model->getLinkHtmlAttributes()) : $model->getLinkHtmlAttributes();
$icon = ($isActive && $activeIcon = $model->activeIcon) ? $activeIcon : (($icon = $model->icon) ? $icon : false);
?>
<?php if ($icon) :
    $model->label = '<img src="' . $icon . '" alt="' . ($icon->title ?: $model->title) . '"> ' . $model->label;
endif ?>
<?= Html::a($model->label, $model->url, $linkOptions) ?>