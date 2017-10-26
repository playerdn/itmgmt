<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\EmailHelpers;
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
                textInput(['readonly'=>'readonly'])->label('Last name') ?>
            </td>
            <td>
                <div  style="margin-left: 50px;">
                    <?= $form->field($model, 'name_i')->
                    textInput(['readonly'=>'readonly'])->label('First name') ?>
                </div>
            </td>
            <td>
                <div  style="margin-left: 50px;">
                    <?= $form->field($model, 'name_o')->
                    textInput(['readonly'=>'readonly'])->label('Midle name') ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <?= $form->field($model, 'login')->textInput() ?>
            </td>
            <td>
                <?php
                    $domains = EmailHelpers::GetOurDomains();
                    $new_dom = [];
                    foreach ($domains as $d ) {
                        $new_dom[$d] = $d;
                    }
                ?>
                <?= $form->field($model, 'domain')->dropDownList($new_dom) ?>
            </td>
        </tr>
        <tr><td>
            <?= $form->field($model, 'guid')->hiddenInput(['value' => $model->guid])->label('') ?>
            <?= $form->field($model, 'passwd')->textInput() ?>
        </td></tr>
        <tr>
            <td><?= $form->field($model, 'spam_f')->checkbox(['checked'=> true])->label('Spam filter') ?></td>
            <td><?= $form->field($model, 'greylist')->checkbox()->label('Greylist') ?></td>
        </tr>
        <tr><td><?= $form->field($model, 'visible_mail')->checkbox()->label('Visible') ?></td></tr>
    </table>
    
    <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 
                'align' => 'right']) ?>
    <?php ActiveForm::end(); ?>
</div>
