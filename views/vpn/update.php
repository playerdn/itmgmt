<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersRecord */

$this->title = 'Update Vpn Users Record: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Vpn Users Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vpn-users-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
