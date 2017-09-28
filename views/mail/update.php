<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\EmailHelpers;
//use app\assets\MailUIAssetBundle;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */

//MailUIAssetBundle::register($this);

$this->title = 'Update Mail Record: ' . EmailHelpers::CreateEmailDescription($model);;
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => EmailHelpers::CreateEmailDescription($model), 
                                    'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mail-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="mail-record-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_f')->textInput(['maxlength' => true])->label('Last name') ?>

    <?= $form->field($model, 'name_i')->textInput(['maxlength' => true])->label('First name') ?>

    <?= $form->field($model, 'name_o')->textInput(['maxlength' => true])->label('Middle name') ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'E_mail')->input('email') ?>

    <?= $form->field($model, 'passwd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spam_f')->checkbox(['label' => 'Spam filter']) ?>

    <?= $form->field($model, 'greylist')->checkbox(['label' => 'Greylist']); ?>

    <?= $form->field($model, 'visible_mail')->checkbox(['label' => 'Visible']); ?>

    <?= $form->field($model, 'aliases')->textarea(['maxlength' => true,
                                                    '' => ''])->label('Copy-to') ?>
    <?= $form->field($model, 'IsEnabled')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


</div>
