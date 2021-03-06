<?php

use im\cms\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\cms\models\ContentWidget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) {
    $form = ActiveForm::begin(['id' => 'content-widget-form', 'options' => ['data-pjax' => 1]]);
} ?>

<?= $form->field($model, 'content', $fieldConfig)->textInput() ?>

<?php if (!isset($form)) {

echo Html::submitButton(Module::t('module', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

ActiveForm::end(); } ?>