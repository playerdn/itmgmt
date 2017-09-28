<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\mail\MailRecord */

$this->title = 'Create Mail Record';
$this->params['breadcrumbs'][] = ['label' => 'Mail Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
