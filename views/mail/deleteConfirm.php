<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\EmailHelpers;
//use app\assets\MailUIAssetBundle;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */
/* @var $modelAliases \yii\base\DynamicModel */
/* @var $depended app\models\mail\MailRecord[] */


$this->title = 'Delete mailbox: ' . $model->E_mail;;
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => EmailHelpers::CreateEmailDescription($model), 
                                    'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Delete';
?>

<div class="mail-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>If you delete mailbox all incoming mail will be rejected with unknown user error.
        As well you lose current mailbox content stored on server</p>
    <p>If you do not want to lose incoming mail you can set up redirection address.
        All new messages will be forwarded to that email, <strong>but mailbox file will be 
            removed anyway</strong></p>
    
    <?php
        if(count($depended)==0) {echo '<div style="display: none">';}
        else {echo '<div>'; }
    ?>
        <h2>Danger!!!</h2>
        <p>Given mailbox has destinations which linked only to him, so if you
            decide to remove this box those destinations will be removed as well:
        </p>
        <ul>
        <?php
            foreach($depended as $item) {print "<li>$item->E_mail</li>"; }
        ?>
        </ul>
    </div>
    <?php $form = ActiveForm::begin(['method' => 'post']); ?>
        <?= $form->field($modelAliases, 'id')->hiddenInput(['value' => $model->id])->label(false) ?>
        <?= $form->field($modelAliases, 'aliases')->textInput()->label('One or more destinations') ?>
        <?= Html::submitButton('Setup redirect',['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>

    <h2>-- Or remove it forever --</h2>
    <?= Html::a('Delete address and mailbox file', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
    ]) ?>
    
</div>
