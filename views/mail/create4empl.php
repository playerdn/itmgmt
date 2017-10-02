<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\EmplPersonsRecord;
use yii\bootstrap\ActiveForm;
use yii\base\DynamicModel;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */

$this->title = 'Create Mailbox for: ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create (for "' . $model->fullName . '")';
?>


<div class="mail-record-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <table>
        <tr>
            <td>
                <?= $form->field($model, 'name_f')->
                textInput(['disabled'=>'disabled'])->label('Last name') ?>
            </td>
            <td>
                <div  style="margin-left: 50px;">
                    <?= $form->field($model, 'name_i')->
                    textInput(['disabled'=>'disabled'])->label('First name') ?>
                </div>
            </td>
            <td>
                <div  style="margin-left: 50px;">
                    <?= $form->field($model, 'name_o')->
                    textInput(['disabled'=>'disabled'])->label('Midle name') ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <?= $form->field($model, 'login')->textInput() ?>
            </td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
