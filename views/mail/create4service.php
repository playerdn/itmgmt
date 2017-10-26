<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\EmailHelpers;
use app\models\EmplPersonsRecord;
use yii\bootstrap\ActiveForm;
use yii\base\DynamicModel;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */

$this->title = 'Create Service Mailbox';
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create';
?>


<div class="mail-record-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name_f')->textInput()->label('Description') ?>
    <table>
        <tr>
            <td><?= $form->field($model, 'login')->textInput() ?></td>
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
        <tr>
            <td><?= $form->field($model, 'passwd')->textInput() ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'spam_f')->checkbox()->label('Spam filter') ?></td>
            <td><?= $form->field($model, 'greylist')->checkbox() ?></td>
        </tr>
    </table>
    <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 
                'align' => 'right']) ?>
    <?php ActiveForm::end(); ?>
</div>
