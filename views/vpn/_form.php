<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersRecord */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vpn-users-record-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'UID')->textInput() ?>

    <?= $form->field($model, 'OVPN_CONF_KIT')->textInput() ?>

    <?= $form->field($model, 'CERT_PASS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REQUEST_DOC_ID')->textInput() ?>

    <?= $form->field($model, 'START_DATE')->textInput() ?>

    <?= $form->field($model, 'EXPIRATION')->textInput() ?>

    <?= $form->field($model, 'LAST_ACCESS')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
