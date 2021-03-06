<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use janisto\ycm\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $name string */
/* @var $form \yii\widgets\ActiveForm */

/** @var $module \janisto\ycm\Module */
$module = Yii::$app->controller->module;

$attributes = [];
foreach ($model->attributeLabels() as $attribute => $label) {
    if (isset($model->tableSchema->columns[$attribute]) && $model->tableSchema->columns[$attribute]->isPrimaryKey === true) {
        continue;
    }
    $attributes[] = $attribute;
}
$attributes = array_filter(array_unique(array_map('trim', $attributes)));

?>

<?= Alert::widget() ?>

<div class="ycm-model-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?php
    foreach ($attributes as $attribute) {
        $module->createWidget($form, $model, $attribute);
    }
    ?>

    <div class="form-group">

        <?php
        if (($module->getHideCreate($model) === true && $this->context->action->id == 'create') ||
            ($module->getHideUpdate($model) === true && $this->context->action->id == 'update')):
            // Save disabled. Add a note?
        else:
            ?>

            <?= Html::submitButton('Save', ['name' => '_save', 'value' => '1', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            <?= Html::submitButton('Save and add another', ['name' => '_addanother', 'value' => '1', 'class' => 'btn btn-default']) ?>

            <?= Html::submitButton('Save and continue editing', ['name' => '_continue', 'value' => '1', 'class' => 'btn btn-default']) ?>

        <?php
        endif;

        if (!$model->isNewRecord && $module->getHideDelete($model) === false): ?>

            <?= Html::a('Delete', ['delete', 'name' => $name, 'pk' => $model->primaryKey], [
                'title' => 'Delete',
                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                //'data-method' => 'post',  // See bugs #7231 and #6642
                //'data-pjax' => '0',
                'class' => 'btn btn-danger',
            ]) ?>

        <?php endif; ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
