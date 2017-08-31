<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vpn-users-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'UID') ?>

    <?= $form->field($model, 'OVPN_CONF_KIT') ?>

    <?= $form->field($model, 'CERT_PASS') ?>

    <?= $form->field($model, 'REQUEST_DOC_ID') ?>

    <?php // echo $form->field($model, 'START_DATE') ?>

    <?php // echo $form->field($model, 'EXPIRATION') ?>

    <?php // echo $form->field($model, 'LAST_ACCESS') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
