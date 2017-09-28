<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\EmailHelpers;

/* @var $this yii\web\View */
/* @var $searchModel app\models\mail\MailSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mail Records';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-record-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mail Record', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'E_mail',
            [
                'attribute' => 'fio',
                'label' => 'Description',
                'format' => 'paragraphs',
                'value' => function ($model) {
                    return EmailHelpers::CreateEmailDescription($model);
                }
            ],
//            'guid',
            'login',
//            'passwd',
            // 'ip',
            // 'date_cr',
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
                'label' => 'Copy-to',
                'attribute' => 'aliases'
            ],
            [
                'label'=> 'Enabled',
                'attribute' => 'IsEnabled',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>
