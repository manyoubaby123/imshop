<?php

use im\cms\backend\widgets\AvailableWidget;
use im\cms\backend\widgets\WidgetArea;
use im\cms\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Template */
/* @var $form yii\widgets\ActiveForm */

$layoutManager = Yii::$app->layoutManager;
if ($model->widgetAreas) {
    $model->layout->widgetAreas = $model->widgetAreas;
}
?>

<div class="template-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'fieldClass' => 'app\modules\formBuilder\widgets\ActiveField']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'layout_id')->dropDownList($model->getLayoutsList(),
        ['prompt' => $model->getDefaultLayout()->getName()]
    ) ?>

    <?php
        Pjax::begin(['id' => 'layout-editor', 'enablePushState' => false]);
        echo $this->render('/layout/_layout_editor', [
            'layout' => $model->layout,
            'form' => $form
        ]);
        Pjax::end();
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$url = Url::to(['layout/form', 'template'=> $model->isNewRecord ? '' : $model->id, 'id' => '']);
$form = json_encode($form);
$script = <<<JS
    var layout = $('[name="Template[layout_id]"]'),
        editor = '#layout-editor';
    layout.on('change', function() {
        $.pjax({container: editor, push: false, type: 'POST', url: '$url' + $(this).val(), data: {form: $form}});
    });
JS;
$this->registerJs($script);
?>
