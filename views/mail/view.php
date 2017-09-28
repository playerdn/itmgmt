<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\EmailHelpers;

/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */

$this->title = EmailHelpers::CreateEmailDescription($model);
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = EmailHelpers::CreateEmailDescription($model); 
?>
<div class="mail-record-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
              'attribute' => 'fio',
              'label' => 'Description',
              'format' => 'paragraphs',
              'value' => function($model) {
                  return EmailHelpers::CreateEmailDescription($model);
              },
            ],
            'login',
            'E_mail',
            'passwd',
            [
              'label' => 'Created',
              'attribute' => 'date_cr',
            ],
            [
                'label'=> 'Spam filter',
                'attribute' => 'spam_f',
                'format'=> 'raw',
                'value' => function($model) {
                  return EmailHelpers::AttributeVisualization($model, 'spam_f');
                }
            ],
            [
                'label'=> 'Greylist',
                'attribute' => 'greylist',
                'format'=> 'raw',
                'value' => function($model) {
                    return EmailHelpers::AttributeVisualization($model, 'greylist');
                }
            ],
            [
                'label'=> 'Visible',
                'attribute' => 'visible_mail',
                'format'=> 'raw',
                'value' => function($model) {
                    return EmailHelpers::AttributeVisualization($model, 'visible_mail');
                }
            ],
            [
                'label' => 'Send copy-to',
                'attribute' => 'aliases'
            ],
            [
              'label' => 'Get copy-from',
              'format' => 'paragraphs',
              'value' => function($model) {
                  $mailRecz = EmailHelpers::GetForwarderForEmail($model);
                  $ret = '';
                  foreach($mailRecz as $rec) {
                      if(strlen($ret)==0) {
                          $ret = $rec->E_mail;
                      } else {
                          $ret .= ', ' . $rec->E_mail;
                      }
                  }
                  return $ret;
              },

            ],
            'IsEnabled',
        ],
    ]) ?>

</div>
