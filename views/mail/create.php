<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\EmplPersonsRecord;
use yii\bootstrap\ActiveForm;
use yii\base\DynamicModel;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */

$this->title = 'Create Mailbox';
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$selectedEmpl = '';
$dynModel = new DynamicModel(compact('selectedEmpl'));

$o = EmplPersonsRecord::EmployersWitoutEmail();
$dropDownItems = ArrayHelper::map($o, 'ID', 'fullName');

//echo '<pre>' . var_dump($o) . '</pre>';
?>
<div class="mail-record-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <table style="border-spacing: 0px 0px; border-collapse: separate;" border="0">
        <tr>
            <td>
                <h2>New mailbox for employee</h2>
                <?php $form = ActiveForm::begin(['id'=> 'select-empl',
                        'method'=>'post'
                  ]); ?>
                <?= $form->field($dynModel, 'selectedEmpl',[
                    'inputOptions' => [
                      'name' => 'selectedEmpl'
                    ]
                  ])->dropDownList($dropDownItems)->label('Select employee')?>
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 
                                'align' => 'right']) ?>
                <?php ActiveForm::end(); ?>
            </td>
            <td style="vertical-align: top;">
                <div style="margin-left: 50px;">
                <h2>New service mailbox</h2>
                    <?= Html::a('Create Service Mailbox', 
                        [Url::toRoute('mail/create-service-email')], 
                        ['class' => 'btn btn-primary']) ?>
                </div>
            </td>
        </tr>
    </table>
</div>
