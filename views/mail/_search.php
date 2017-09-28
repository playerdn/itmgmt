<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name_f') ?>

    <?= $form->field($model, 'name_i') ?>

    <?= $form->field($model, 'name_o') ?>

    <?= $form->field($model, 'guid') ?>

    <?php // echo $form->field($model, 'login') ?>

    <?php // echo $form->field($model, 'E_mail') ?>

    <?php // echo $form->field($model, 'passwd') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'date_cr') ?>

    <?php // echo $form->field($model, 'spam_f') ?>

    <?php // echo $form->field($model, 'greylist') ?>

    <?php // echo $form->field($model, 'IsLocal') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'visible_mail') ?>

    <?php // echo $form->field($model, 'komn') ?>

    <?php // echo $form->field($model, 'otdel') ?>

    <?php // echo $form->field($model, 'aliases') ?>

    <?php // echo $form->field($model, 'IsEnabled') ?>

    <?php // echo $form->field($model, 'IsDismiss') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
